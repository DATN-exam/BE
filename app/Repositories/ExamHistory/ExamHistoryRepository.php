<?php

namespace App\Repositories\ExamHistory;

use App\Enums\ExamHistory\ExamHistoryStatus;
use App\Models\Exam;
use App\Models\ExamHistory;
use App\Models\User;
use App\Repositories\BaseRepository;

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
}
