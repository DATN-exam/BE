<?php

namespace App\Services\Admin\Cron;

use App\Notifications\Student\AutoSubmitNotification;
use App\Notifications\Student\EndExamNotification;
use App\Notifications\Student\StartExamNotification;
use App\Repositories\Exam\ExamRepositoryInterface;
use App\Repositories\ExamHistory\ExamHistoryRepositoryInterface;
use App\Services\Site\Student\ExamService;
use Illuminate\Support\Facades\Notification;


class ExamCronService
{
    public function __construct(
        protected ExamHistoryRepositoryInterface $examHistoryRepo,
        protected ExamRepositoryInterface $examRepo,
        protected ExamService $exanSer
    ) {
        //
    }

    public function autoSubmit()
    {
        $examHistoryExpried = $this->examHistoryRepo->getExamHistoryExpried();
        $examHistoryExpried->each(function ($examHis) {
            $data = $this->exanSer->submit($examHis);
            $student = $data->student;
            Notification::send($student, new AutoSubmitNotification($data));
        });
    }

    public function notificationExamStart()
    {
        $examsStart = $this->examRepo->getExamsStartNotification();
        $examsStart->each(function ($exam) {
            $exam->classroom->students->each(function ($student) use ($exam) {
                Notification::send($student, new StartExamNotification($exam, $student));
                $exam->notification_start = true;
                $exam->save();
            });
        });
    }

    public function notificationExamEnd()
    {
        $examsEnd = $this->examRepo->getExamsEndNotification();
        $examsEnd->each(function ($exam) {
            $exam->classroom->students->each(function ($student) use ($exam) {
                Notification::send($student, new EndExamNotification($exam, $student));
                $exam->notification_end = true;
                $exam->save();
            });
        });
    }
}
