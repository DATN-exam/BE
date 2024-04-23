<?php

namespace App\Services\Site\Teacher\Question;

use App\Models\Answer;
use App\Models\Question;
use App\Repositories\Answer\AnswerRepositoryInterface;
use App\Services\BaseService;


class AnswerService extends BaseService
{
    public function __construct(
        protected AnswerRepositoryInterface $answerRepo
    ) {
        //
    }

    public function insert($answers, Question $question)
    {
        $now = now();
        $data = collect($answers)->map(function ($answer) use ($question, $now) {
            return [
                'question_id' => $question->id,
                'answer' => $answer['answer'],
                'is_correct' => $answer['is_correct'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();
        return $this->answerRepo->insert($data);
    }
}
