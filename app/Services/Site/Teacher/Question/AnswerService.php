<?php

namespace App\Services\Site\Teacher\Question;

use App\Models\Answer;
use App\Models\Question;
use App\Repositories\Answer\AnswerRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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
                ...$answer,
                'question_id' => $question->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();
        return $this->answerRepo->insert($data);
    }

    public function deleteAnswers(Question $question, $answersUpdate)
    {
        $answerUpdateIds = Arr::pluck($answersUpdate, 'id');
        return $this->answerRepo->deleteAnswerNotUpdate($question, $answerUpdateIds);
    }

    public function updateAnswers($updateAnswers)
    {
        foreach ($updateAnswers as $answer) {
            $data = Arr::except($answer, ['id']);
            $this->answerRepo->updateById($answer['id'], $data);
        }
    }
}
