<?php

namespace App\Repositories\Question;

use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
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
        return $this->model
            ->where('set_question_id', $setQuestion->id)
            ->when(isset($filters['question']), function ($query) use ($filters) {
                return $query->where('question', 'like', '%' . $filters['question'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', QuestionStatus::getValueByKey($filters['status']));
            })
            ->when(isset($filters['type']), function ($query) use ($filters) {
                return $query->where('type', QuestionType::getValueByKey($filters['type']));
            })
            ->when(isset($filters['is_testing']), function ($query) use ($filters) {
                return $query->where('is_testing', $filters['is_testing']);
            })
            ->with(['answers'])
            ->orderBy($filters['sort_column'] ?? 'id', $filters['sort_type'] ?? 'ASC')
            ->paginate($filters['per_page'] ?? 10);
    }
}
