<?php

namespace App\Repositories\Exam;

use App\Models\Classroom;
use App\Models\Exam;
use App\Repositories\BaseRepository;

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
}
