<?php

namespace App\Repositories\Exam;

use App\Enums\ExamHistory\ExamHistoryType;
use App\Models\Classroom;
use App\Models\Exam;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ExamRepository extends BaseRepository implements ExamRepositoryInterface
{
    public function getModel()
    {
        return Exam::class;
    }

    public function getList(Classroom $classroom, $filters)
    {
        return $this->model
            ->where('classroom_id', $classroom->id)
            ->paginate($filters['per_page'] ?? 15);;
    }

    public function allOfClassroom(Classroom $classroom)
    {
        return $this->model
            ->where('classroom_id', $classroom->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function getTop(Exam $exam)
    {
        return $exam->examHistories()
            ->select([
                '*',
                DB::raw('TIMESTAMPDIFF(SECOND, start_time, submit_time) AS time_taken')
            ])
            ->where('is_submit', true)
            ->where('type', ExamHistoryType::TEST)
            ->orderBy('total_score', 'desc')
            ->orderBy('time_taken')
            ->with('student')
            ->get();
    }
}
