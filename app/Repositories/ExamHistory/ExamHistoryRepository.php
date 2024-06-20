<?php

namespace App\Repositories\ExamHistory;

use App\Enums\ExamHistory\ExamHistoryStatus;
use App\Models\Exam;
use App\Models\ExamHistory;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class ExamHistoryRepository extends BaseRepository implements ExamHistoryRepositoryInterface
{
    public function getModel()
    {
        return ExamHistory::class;
    }

    public function getCurrentTest(User $student, Exam $exam)
    {
        return $this->model
            ->where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->where('status', ExamHistoryStatus::ACTIVE)
            ->first();
    }

    public function getExamHistoryExpried()
    {
        $currentDateTime = Carbon::now();
        return $this->model->where('is_submit', false)
            ->whereHas('exam', function ($query) use ($currentDateTime) {
                $query->whereRaw("DATE_ADD(start_time, INTERVAL TIME_TO_SEC(working_time) SECOND) < ?", [$currentDateTime]);
            })->get();
    }
}
