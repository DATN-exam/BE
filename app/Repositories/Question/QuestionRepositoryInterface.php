<?php

namespace App\Repositories\Question;

use App\Models\SetQuestion;
use App\Repositories\RepositoryInterface;

interface QuestionRepositoryInterface extends RepositoryInterface
{
    public function paginate(SetQuestion $setQuestion, $filters);
}
