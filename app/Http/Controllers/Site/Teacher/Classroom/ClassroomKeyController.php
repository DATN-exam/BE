<?php

namespace App\Http\Controllers\Site\Teacher\Classroom;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Teacher\ClassroomKeyRequest;
use App\Http\Resources\Site\Teacher\ClassroomKeyResource;
use App\Models\Classroom;
use App\Models\ClassroomKey;
use App\Services\Site\Teacher\Classroom\ClassroomKeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClassroomKeyController extends BaseApiController
{
    public function __construct(protected ClassroomKeyService $keySer)
    {
        //
    }

    public function store(Classroom $classroom, ClassroomKeyRequest $rq)
    {
        try {
            $key = $this->keySer->setRequestValidated($rq)->store($classroom);
            return $this->sendResponse(
                ClassroomKeyResource::make($key)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function index(Classroom $classroom, Request $rq)
    {
        try {
            $keys = $this->keySer->setRequest($rq)->paginate($classroom);
            return $this->sendResourceResponse(
                ClassroomKeyResource::collection($keys)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function block(Classroom $classroom, ClassroomKey $classroomKey)
    {
        try {
            $this->keySer->block($classroomKey);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function active(Classroom $classroom, ClassroomKey $classroomKey)
    {
        try {
            $this->keySer->active($classroomKey);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
