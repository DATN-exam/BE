<?php

namespace App\Repositories\Answer;

use App\Models\Question;
use App\Repositories\RepositoryInterface;

interface AnswerRepositoryInterface extends RepositoryInterface
{
    public function deleteAnswerNotUpdate(Question $question, $answerUpdateIds);
}
