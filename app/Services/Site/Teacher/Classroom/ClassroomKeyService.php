<?php

namespace App\Services\Site\Teacher\Classroom;

use App\Enums\Classroom\ClassroomKeyStatus;
use App\Models\Classroom;
use App\Models\ClassroomKey;
use App\Repositories\Classroom\ClassroomKeyRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Str;

class ClassroomKeyService extends BaseService
{
    public function __construct(
        protected ClassroomKeyRepositoryInterface $keyRepo
    ) {
        //
    }

    public function store(Classroom $classroom)
    {
        return $this->keyRepo->create([
            ...$this->data,
            'classroom_id' => $classroom->id,
            'key' => Str::random(8) . '-' . $classroom->id
        ]);
    }

    public function paginate(Classroom $classroom)
    {
        return $this->keyRepo->paginateOfClassroom($classroom, $this->data);
    }

    public function block(ClassroomKey $key)
    {
        return $this->keyRepo->update($key, [
            'status' => ClassroomKeyStatus::INACTIVE
        ]);
    }

    public function active(ClassroomKey $key)
    {
        return $this->keyRepo->update($key, [
            'status' => ClassroomKeyStatus::ACTIVE
        ]);
    }
}
