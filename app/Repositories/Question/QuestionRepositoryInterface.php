<?php

namespace App\Repositories\Question;

use App\Models\Exam;
use App\Models\SetQuestion;
use App\Repositories\RepositoryInterface;

interface QuestionRepositoryInterface extends RepositoryInterface
{
    public function paginate(SetQuestion $setQuestion, $filters);

    public function exportWord(SetQuestion $setQuestion, $filters);

    public function getQuestionExamRandom(Exam $exam, $typeQuestion);
}
