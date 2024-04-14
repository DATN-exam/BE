<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherRegistration;
use App\Services\Admin\Teacher\RegistrationService;
use Illuminate\Support\Facades\Log;
use Throwable;

class TeacherRegistrationController extends Controller
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
}
