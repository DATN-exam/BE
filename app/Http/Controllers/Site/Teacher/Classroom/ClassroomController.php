<?php

namespace App\Http\Controllers\Site\Teacher\Classroom;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Teacher\ClassroomRequest;
use App\Http\Requests\Site\Teacher\ClassroomUpdateRequest;
use App\Http\Resources\Site\ClassroomResource;
use App\Models\Classroom;
use App\Services\Site\Teacher\Classroom\ClassroomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClassroomController extends BaseApiController
{

    public function __construct(protected ClassroomService $classroomSer)
    {
        //
    }

    public function index(Request $rq)
    {
        try {
            $classrooms = $this->classroomSer->setRequest($rq)->paginate();
            return $this->sendResourceResponse(
                ClassroomResource::collection($classrooms)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function store(ClassroomRequest $rq)
    {
        try {
            $classroom = $this->classroomSer->setRequestValidated($rq)->store();
            return $this->sendResourceResponse(
                ClassroomResource::make($classroom)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function update(Classroom $classroom, ClassroomUpdateRequest $rq)
    {
        try {
            $classroom = $this->classroomSer->setRequestValidated($rq)->update($classroom);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function show(Classroom $classroom)
    {
        try {
            return $this->sendResourceResponse(
                ClassroomResource::make($classroom)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
