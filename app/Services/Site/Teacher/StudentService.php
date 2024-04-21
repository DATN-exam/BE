<?php

namespace App\Services\Site\Teacher;

use App\Models\Classroom;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;

class StudentService extends BaseService
{
    public function __construct(
        protected UserRepositoryInterface $studentRepo
    ) {
        //
    }

    public function paginate(Classroom $classroom)
    {
        return $this->studentRepo->paginateStudentOfClassroom($this->data, $classroom->id);
    }
}
