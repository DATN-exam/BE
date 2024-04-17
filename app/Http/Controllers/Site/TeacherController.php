<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Teacher\RegisterRequest;
use App\Http\Resources\Site\TeacherRegistrationResource;
use App\Services\Site\TeacherService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class TeacherController extends BaseApiController
{
    public function __construct(protected TeacherService $teacherSer)
    {
        //
    }

    public function register(RegisterRequest $rq)
    {
        try {
            $registration = $this->teacherSer->setRequestValidated($rq)->register();
            return $this->sendResponse(TeacherRegistrationResource::make($registration));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
