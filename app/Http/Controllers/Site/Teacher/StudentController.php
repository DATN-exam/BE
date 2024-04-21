<?php

namespace App\Http\Controllers\Site\Teacher;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\Site\StudentResource;
use App\Models\Classroom;
use App\Services\Site\Teacher\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class StudentController extends BaseApiController
{
    public function __construct(protected StudentService $studentSer)
    {
        //
    }

    public function index(Classroom $classroom, Request $rq)
    {
        try {
            $students = $this->studentSer->setRequest($rq)->paginate($classroom);
            return $this->sendResourceResponse(
                StudentResource::collection($students)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
