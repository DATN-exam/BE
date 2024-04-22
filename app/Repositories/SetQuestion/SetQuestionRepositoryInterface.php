<?php

namespace App\Repositories\SetQuestion;

use App\Repositories\RepositoryInterface;

interface SetQuestionRepositoryInterface extends RepositoryInterface
{
    public function paginateOfTeacher($teacherId,$filters);
}
