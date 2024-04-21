<?php

namespace App\Repositories\Classroom;

use App\Models\Classroom;
use App\Models\User;
use App\Repositories\RepositoryInterface;

interface ClassroomStudentRepositoryInterface extends RepositoryInterface
{
    public function getByClassroomStudent($classroomId, $studentId);

    public function addStudent($classroomId, User $student, $keyJoin = null);
}
