<?php

namespace App\Repositories\ExamAnswer;

use App\Models\ExamAnwser;
use App\Repositories\BaseRepository;

class ExamAnswerRepository extends BaseRepository implements ExamAnswerRepositoryInterface
{
    public function getModel()
    {
        return ExamAnwser::class;
    }
}
