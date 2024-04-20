<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\ClassroomKey;
use App\Models\User;

class ClassroomKeyPolicy
{
    public function block(User $teacher, ClassroomKey $key, Classroom $classroom)
    {
        return $classroom->id === $key->classroom_id;
    }

    public function active(User $teacher, ClassroomKey $key, Classroom $classroom)
    {
        return $classroom->id === $key->classroom_id;
    }
}
