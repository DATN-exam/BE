<?php

namespace App\Repositories\SetQuestion;

use App\Enums\Question\QuestionLevel;
use App\Enums\Question\QuestionStatus;
use App\Enums\Question\QuestionType;
use App\Enums\Question\SetQuestionStatus;
use App\Models\SetQuestion;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class SetQuestionRepository extends BaseRepository implements SetQuestionRepositoryInterface
{
    public function getModel()
    {
        return SetQuestion::class;
    }

    private function baseList($filters)
    {
        return $this->model
            ->when(isset($filters['title']), function ($query) use ($filters) {
                return $query->where('title', 'like', '%' . $filters['title'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', SetQuestionStatus::getValueByKey($filters['status']));
            })
            ->orderBy($filters['sort_column'] ?? 'created_at', $filters['sort_type'] ?? 'DESC');
    }

    public function paginateOfTeacher($teacherId, $filters)
    {
        return $this->baseList($filters)
            ->where('teacher_id', $teacherId)
            ->withCount('questions')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getSetQuestionReady(User $teacher)
    {
        return $this->model
            ->where('teacher_id', $teacher->id)
            ->where('status', SetQuestionStatus::ACTIVE)
            ->withCount(['questions as question_hard_count' => function (Builder $query) {
                $query
                    ->where('is_testing', false)
                    ->where('status', QuestionStatus::ACTIVE)
                    ->where('level', QuestionLevel::HARD);
            }])
            ->withCount(['questions as question_medium_count' => function (Builder $query) {
                $query
                    ->where('is_testing', false)
                    ->where('status', QuestionStatus::ACTIVE)
                    ->where('level', QuestionLevel::MEDIUM);
            }])
            ->withCount(['questions as question_easy_count' => function (Builder $query) {
                $query
                    ->where('is_testing', false)
                    ->where('status', QuestionStatus::ACTIVE)
                    ->where('level', QuestionLevel::EASY);
            }])
            ->having('question_hard_count', '>', 1)
            ->orHaving('question_medium_count', '>', 1)
            ->orHaving('question_easy_count', '>', 1)
            ->get();
    }
}
