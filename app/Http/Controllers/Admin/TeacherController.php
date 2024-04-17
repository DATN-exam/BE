<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\Student\BlockRequest;
use App\Http\Resources\Admin\TeacherResource;
use App\Models\User;
use App\Services\Admin\Teacher\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TeacherController extends BaseApiController
{
    public function __construct(protected TeacherService $teacherSer)
    {
        //
    }

    public function index(Request $rq)
    {
        try {
            $teachers = $this->teacherSer->setRequest($rq)->paginate();
            return $this->sendResourceResponse(
                TeacherResource::collection($teachers)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function block(User $teacher, BlockRequest $rq)
    {
        try {
            $data = $this->teacherSer->setRequestValidated($rq)->block($teacher);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function active(User $teacher, BlockRequest $rq)
    {
        try {
            $data = $this->teacherSer->setRequestValidated($rq)->active($teacher);
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
