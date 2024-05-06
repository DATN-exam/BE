<?php

namespace App\Services\Site\Teacher;

use App\Enums\Classroom\ClassroomStudentStatus;
use App\Models\Classroom;
use App\Models\User;
use App\Repositories\Classroom\ClassroomStudentRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;

class StudentService extends BaseService
{
    public function __construct(
        protected UserRepositoryInterface $studentRepo,
        protected ClassroomStudentRepositoryInterface $classroomStudentRepo
    ) {
        //
    }

    public function paginate(Classroom $classroom)
    {
        return $this->studentRepo->paginateStudentOfClassroom($this->data, $classroom);
    }

    public function block(Classroom $classroom, User $student)
    {
        return $this->classroomStudentRepo->updateStatusStudent($classroom, $student, ClassroomStudentStatus::BLOCK);
    }

    public function active(Classroom $classroom, User $student)
    {
        return $this->classroomStudentRepo->updateStatusStudent($classroom, $student, ClassroomStudentStatus::ACTIVE);
    }
}
