<?php

namespace App\Repositories\Classroom;

use App\Models\Classroom;
use App\Repositories\RepositoryInterface;

interface ClassroomKeyRepositoryInterface extends RepositoryInterface
{
    public function paginateOfClassroom(Classroom $classroom, $filters);
}
