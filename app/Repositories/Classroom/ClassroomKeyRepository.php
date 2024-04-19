<?php

namespace App\Repositories\Classroom;

use App\Models\Classroom;
use App\Models\ClassroomKey;
use App\Repositories\BaseRepository;

class ClassroomKeyRepository extends BaseRepository implements ClassroomKeyRepositoryInterface
{
    public function getModel()
    {
        return ClassroomKey::class;
    }

    public function paginateOfClassroom(Classroom $classroom, $filters)
    {
        return $this->model
            ->where('classroom_id', $classroom->id)
            ->paginate($filters['per_page'] ?? 10);
    }
}
