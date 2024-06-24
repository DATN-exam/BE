<?php

namespace App\Services\Admin\Cron;

use App\Notifications\Student\AutoSubmitNotification;
use App\Repositories\Exam\ExamRepositoryInterface;
use App\Repositories\ExamHistory\ExamHistoryRepositoryInterface;
use App\Services\Site\Student\ExamService;
use Illuminate\Support\Facades\Log;
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

    // public function notificationExamStart()
    // {
    //     $examStart
    // }
}
