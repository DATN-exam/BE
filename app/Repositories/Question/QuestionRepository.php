<?php

namespace App\Repositories\Question;

use App\Enums\Question\QuestionLevel;
use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
use App\Models\Exam;
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

    public function exportWord(SetQuestion $setQuestion, $filters)
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
            ->get();
    }

    public function getQuestionExamRandom(Exam $exam, $typeQuestion)
    {
        $questionHard = $this->model
            ->where('set_question_id', $exam->set_question_id)
            ->where('status', QuestionStatus::ACTIVE)
            ->where('is_testing', $typeQuestion)
            ->where('level', QuestionLevel::HARD)
            ->inRandomOrder()
            ->take($exam->number_question_hard)
            ->get();
        $questionMedium = $this->model
            ->where('set_question_id', $exam->set_question_id)
            ->where('status', QuestionStatus::ACTIVE)
            ->where('is_testing', false)
            ->where('level', QuestionLevel::MEDIUM)
            ->inRandomOrder()
            ->take($exam->number_question_medium)
            ->get();
        $questionEasy = $this->model
            ->where('set_question_id', $exam->set_question_id)
            ->where('status', QuestionStatus::ACTIVE)
            ->where('is_testing', false)
            ->where('level', QuestionLevel::EASY)
            ->inRandomOrder()
            ->take($exam->number_question_easy)
            ->get();

        return $questionHard->concat($questionMedium)->concat($questionEasy);
    }
}
