<?php

namespace App\Http\Controllers\Site\Teacher;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\Site\Teacher\StudentResource;
use App\Models\Classroom;
use App\Models\User;
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

    public function export(Classroom $classroom, Request $rq)
    {
        $students = $this->studentSer->setRequest($rq)->export($classroom);
        return $this->sendCsvResponse($students, 'students.xlsx');
        try {
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function block(Classroom $classroom, User $student)
    {
        try {
            $this->studentSer->block($classroom, $student);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function active(Classroom $classroom, User $student)
    {
        try {
            $this->studentSer->active($classroom, $student);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
