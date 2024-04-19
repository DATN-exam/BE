<?php

namespace App\Services\Site\Teacher;

use App\Enums\Classroom\ClassroomStatus;
use App\Models\Classroom;
use App\Repositories\Classroom\ClassroomKeyRepositoryInterface;
use App\Repositories\Classroom\ClassroomRepositoryInterface;
use App\Services\BaseService;
use Carbon\Carbon;
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
}
