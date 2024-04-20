<?php

namespace App\Repositories\Classroom;

use App\Models\Classroom;
use App\Models\ClassroomKey;
use App\Repositories\RepositoryInterface;

interface ClassroomKeyRepositoryInterface extends RepositoryInterface
{
    public function paginateOfClassroom(Classroom $classroom, $filters);

    public function check($key);

    public function decrement(ClassroomKey $key);
}
