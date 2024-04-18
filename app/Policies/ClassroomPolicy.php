<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;

class ClassroomPolicy
{
    public function teacherUpdate(User $teacher, Classroom $classroom)
    {
        return $teacher->id === $classroom->teacher_id;
    }
}
