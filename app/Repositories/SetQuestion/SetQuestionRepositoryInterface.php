<?php

namespace App\Repositories\SetQuestion;

use App\Models\User;
use App\Repositories\RepositoryInterface;

interface SetQuestionRepositoryInterface extends RepositoryInterface
{
    public function paginateOfTeacher($teacherId, $filters);

    public function getSetQuestionReady(User $teacher);

    public function getNumberSetQuestion();
}
