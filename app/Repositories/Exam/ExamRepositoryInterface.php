<?php

namespace App\Repositories\Exam;

use App\Models\Classroom;
use App\Models\Exam;
use App\Repositories\RepositoryInterface;

interface ExamRepositoryInterface extends RepositoryInterface
{
    public function getList(Classroom $classroom, $filters);

    public function allOfClassroom(Classroom $classroom);

    public function getTop(Exam $exam);
}
