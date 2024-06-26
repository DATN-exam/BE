<?php

namespace App\Repositories\Classroom;

use App\Enums\Classroom\ClassroomKeyStatus;
use App\Enums\Classroom\ClassroomStatus;
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
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('name', 'like', '%' . $filters['name'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', ClassroomKeyStatus::getValueByKey($filters['status']));
            })
            ->orderBy($filters['sort_column'] ?? 'created_at', $filters['sort_type'] ?? 'DESC')
            ->paginate($filters['per_page'] ?? 10);
    }

    public function check($key)
    {
        $now = now();
        return $this->model
            ->where('key', $key)
            ->where('status', ClassroomKeyStatus::ACTIVE)
            ->where('remaining', '>', 0)
            ->where('expired', '>', $now)
            ->whereHas('classroom', function ($query) {
                return $query->where('classrooms.status', ClassroomStatus::ACTIVE);
            })
            ->with('classroom')
            ->first();
    }

    public function decrement(ClassroomKey $key)
    {
        $key->decrement('remaining');
        $key->save();
        return $key;
    }
}
