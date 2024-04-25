<?php

namespace App\Repositories\SetQuestion;

use App\Models\SetQuestion;
use App\Repositories\BaseRepository;

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
            ->orderBy($filters['sort_column'] ?? 'created_at', $filters['sort_type'] ?? 'DESC');
    }

    public function paginateOfTeacher($teacherId, $filters)
    {
        return $this->baseList($filters)
            ->where('teacher_id', $teacherId)
            ->paginate($filters['per_page'] ?? 15);
    }
}
