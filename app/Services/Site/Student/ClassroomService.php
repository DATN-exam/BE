<?php

namespace App\Services\Site\Student;

use App\Enums\Classroom\ClassroomStudentStatus;
use App\Repositories\Classroom\ClassroomKeyRepositoryInterface;
use App\Repositories\Classroom\ClassroomRepositoryInterface;
use App\Repositories\Classroom\ClassroomStudentRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClassroomService extends BaseService
{
    public function __construct(
        protected ClassroomRepositoryInterface $classroomRepo,
        protected ClassroomKeyRepositoryInterface $keyRepo,
        protected ClassroomStudentRepositoryInterface $classroomStudentRepo
    ) {
        //
    }

    public function join()
    {
        $key = $this->keyRepo->check($this->data['key']);
        if ($key === null) {
            return throw new \Exception(__('alert.classroom.key.error'));
        }
        $student = auth('api')->user();
        $classroomStudent = $this->classroomStudentRepo
            ->getByClassroomStudent($key->classroom->id, $student->id);
        if ($classroomStudent && $classroomStudent->status === ClassroomStudentStatus::ACTIVE) {
            return throw new \Exception(__('alert.classroom.key.actived'));
        }
        if ($classroomStudent && $classroomStudent->status === ClassroomStudentStatus::BLOCK) {
            return throw new \Exception(__('alert.classroom.student.blocked'));
        }
        DB::beginTransaction();
        try {
            $this->keyRepo->decrement($key);
            $this->classroomStudentRepo->addStudent($key->classroom_id, $student, $this->data['key']);
            DB::commit();
            return;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function paginate()
    {
        $student = auth('api')->user();
        return $this->classroomRepo->paginateStudent($this->data, $student->id);
    }
}
