<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\Admin\TeacherRegistrationResource;
use App\Models\TeacherRegistration;
use App\Services\Admin\Teacher\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;
use InvalidArgumentException;

class TeacherRegistrationController extends BaseApiController
{
    public function __construct(protected RegistrationService $registrationSer)
    {
        //
    }

    public function confirm(TeacherRegistration $teacherRegistration)
    {
        try {
            $this->registrationSer->confirmRegistration($teacherRegistration);
            return $this->sendResponse([
                'message' => __('alert.update_successful'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function show(Request $rq)
    {
        try {
            $registrations = $this->registrationSer->paginate($rq->all());
            return $this->sendResourceResponse(TeacherRegistrationResource::collection($registrations));
        } catch (InvalidArgumentException $e) {
            return $this->sendError(__('alert.params.invalid'), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function deny(TeacherRegistration $teacherRegistration)
    {
        try {
            $this->registrationSer->denyRegistration($teacherRegistration);
            return $this->sendResponse([
                'message' => __('alert.update_successful'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
