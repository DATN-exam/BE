<?php

namespace App\Http\Controllers\Site\Student;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\Site\ClassroomResource;
use App\Models\Classroom;
use App\Models\ClassroomKey;
use App\Services\Site\Student\ClassroomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClassroomController extends BaseApiController
{
    public function __construct(protected ClassroomService $classroomSer)
    {
    }

    public function join(ClassroomKey $classroomKey)
    {
        try {
            $this->classroomSer->join($classroomKey);
            return $this->sendResponse([
                'message' => __('alert.classroom.join.success')
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
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

    public function show(Classroom $classroom)
    {
        try {
            return $this->sendResponse(
                ClassroomResource::make($classroom)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
