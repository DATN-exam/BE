<?php

namespace App\Repositories\Question;

use App\Models\Question;
use App\Models\SetQuestion;
use App\Repositories\BaseRepository;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function getModel()
    {
        return Question::class;
    }

    public function paginate(SetQuestion $setQuestion, $filters)
    {
        return $this->model->where('set_question_id', $setQuestion->id)
            ->with(['answers'])
            ->paginate($filters['per_page'] ?? 10);
    }
}
