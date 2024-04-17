<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\Student\BlockRequest;
use App\Http\Resources\Admin\StudentResource;
use App\Models\User;
use App\Services\Admin\Student\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class StudentController extends BaseApiController
{
    public function __construct(protected StudentService $studentSer)
    {
        //
    }

    public function index(Request $rq)
    {
        try {
            $students = $this->studentSer->setRequest($rq)->paginate();
            return $this->sendResourceResponse(
                StudentResource::collection($students)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function show(User $student)
    {
        try {
            $student = $this->studentSer->show($student);
            return $this->sendResponse(
                StudentResource::make($student)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function update(User $student)
    {
        //
    }

    public function block(User $student, BlockRequest $rq)
    {
        try {
            $data = $this->studentSer->setRequestValidated($rq)->block($student);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function active(User $student, BlockRequest $rq)
    {
        try {
            $data = $this->studentSer->setRequestValidated($rq)->active($student);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
