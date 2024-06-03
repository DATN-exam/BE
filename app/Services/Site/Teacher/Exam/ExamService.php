<?php

namespace App\Services\Site\Teacher\Exam;

use App\Models\Classroom;
use App\Repositories\Exam\ExamRepositoryInterface;
use App\Services\BaseService;


class ExamService extends BaseService
{
    public function __construct(protected ExamRepositoryInterface $examRepo)
    {
        //
    }
    public function create(Classroom $classRoom)
    {
        return $this->examRepo->create([...$this->data, 'classroom_id' => $classRoom->id]);
    }

    public function getList(Classroom $classroom)
    {
        return $this->examRepo->getList($classroom, $this->data);
    }
}
