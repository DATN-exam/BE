<?php

namespace App\Services\Site\Teacher\Classroom;

use App\Enums\Classroom\ClassroomStatus;
use App\Models\Classroom;
use App\Repositories\Classroom\ClassroomRepositoryInterface;
use App\Services\BaseService;


class ClassroomService extends BaseService
{
    public function __construct(
        protected ClassroomRepositoryInterface $classroomRepo
    ) {
        //
    }

    public function paginate()
    {
        $teacher = auth('api')->user();
        return $this->classroomRepo->paginateOfTeacher($this->data, $teacher->id);
    }

    public function store()
    {
        $teacher = auth('api')->user();
        $dataCreate = [
            ...$this->data,
            'status' => ClassroomStatus::getValueByKey($this->data['status']),
            'teacher_id' => $teacher->id
        ];
        return $this->classroomRepo->create($dataCreate);
    }

    public function update(Classroom $classroom)
    {
        if (isset($this->data['status'])) {
            $this->data['status'] = ClassroomStatus::getValueByKey($this->data['status']);
        }
        return $this->classroomRepo->update($classroom, $this->data);
    }
}
