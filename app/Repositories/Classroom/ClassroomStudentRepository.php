<?php

namespace App\Repositories\Classroom;

use App\Enums\Classroom\ClassroomStatus;
use App\Enums\Classroom\ClassroomStudentStatus;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\User;
use App\Repositories\BaseRepository;

class ClassroomStudentRepository extends BaseRepository implements ClassroomStudentRepositoryInterface
{
    public function getModel()
    {
        return ClassroomStudent::class;
    }

    public function getByClassroomStudent($classroomId, $studentId)
    {
        return $this->model
            ->where('classroom_id', $classroomId)
            ->where('student_id', $studentId)
            ->first();
    }

    public function addStudent($classroomId, User $student, $keyJoin = '')
    {
        return $this->model->create([
            'student_id' => $student->id,
            'classroom_id' => $classroomId,
            'status' => ClassroomStudentStatus::ACTIVE,
            'type_join' => $keyJoin
        ]);
    }

    private function baseList($filters)
    {
        return $this->model
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('name', 'like', $filters['name'] . '%');
            });
    }
}
