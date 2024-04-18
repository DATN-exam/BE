<?php

namespace App\Repositories\Classroom;

use App\Models\TeacherRegistration;
use App\Models\User;
use App\Repositories\RepositoryInterface;

interface ClassroomRepositoryInterface extends RepositoryInterface
{
    public function paginateOfTeacher($filters, $teacherId);
}
