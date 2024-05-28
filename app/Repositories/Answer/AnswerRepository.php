<?php

namespace App\Repositories\Answer;

use App\Models\Answer;
use App\Models\Question;
use App\Repositories\BaseRepository;

class AnswerRepository extends BaseRepository implements AnswerRepositoryInterface
{
    public function getModel()
    {
        return Answer::class;
    }

    public function deleteAnswerNotUpdate(Question $question, $answerUpdateIds)
    {
        return $this->model
            ->where('question_id', $question->id)
            ->whereNotIn('id', $answerUpdateIds)->delete();
    }
}
