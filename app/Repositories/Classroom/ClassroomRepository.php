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
            ->when(isset($filters['id']), function ($query) use ($filters) {
                return $query->where('id', $filters['id']);
            })
            // ->when(isset($filters['name_teacher']), function ($query) use ($filters) {
            //     return $query->whereHas('teacher', function ($query) use ($filters) {
            //         return $query->where('first_name', 'like', $filters['name_teacher'] . '%')
            //             ->orWhere->where('last_name', 'like', $filters['name_teacher'] . '%');
            //     });
            // })
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('name', 'like', '%' . $filters['name'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', ClassroomStatus::getValueByKey($filters['status']));
            })
            ->orderBy($filters['sort_column'] ?? 'created_at', $filters['sort_type'] ?? 'DESC');
    }

    public function paginateStudent($filters, $studentId)
    {
        return $this->baseList($filters)
            ->whereHas('students', function ($query) use ($studentId) {
                return $query->where('student_id', $studentId);
            })
            ->paginate($filters['per_page'] ?? 10);
    }
}
