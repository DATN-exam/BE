<?php

namespace App\Repositories\Classroom;

use App\Enums\Classroom\ClassroomStatus;
use App\Models\Classroom;
use App\Repositories\BaseRepository;

class ClassroomRepository extends BaseRepository implements ClassroomRepositoryInterface
{
    public function getModel()
    {
        return Classroom::class;
    }

    public function paginateOfTeacher($filters, $techerId)
    {
        return $this->baseList($filters)
            ->where('teacher_id', $techerId)
            ->paginate($filters['per_page'] ?? 10);
    }

    private function baseList($filters)
    {
        return $this->model
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('name', 'like', '%' . $filters['name'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', ClassroomStatus::getValueByKey($filters['status']));
            })
            ->when(isset($filters['sort']), function ($query) use ($filters) {
                return $query->modelSort($filters['sort']);
            });
    }

    public function paginateStudent($filters, $studentId)
    {
        return $this->baseList($filters)
            ->whereHas('students', function ($query) use($studentId)  {
                return $query->where('student_id', $studentId);
            })
            ->paginate($filters['per_page'] ?? 10);
    }
}
