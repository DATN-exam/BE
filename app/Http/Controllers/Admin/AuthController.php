<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Resources\Admin\StudentResource;
use App\Services\Admin\AuthService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends BaseApiController
{
    public function __construct(protected AuthService $authSer)
    {
        //
    }

    public function login(LoginRequest $rq)
    {
        try {
            $data = $this->authSer->setRequestValidated($rq)->login();
            return $this->sendResponse($data);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout()
    {
        try {
            $this->authSer->logout();
            return $this->sendResponse([
                'message' => __('alert.auth.logout.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function profile()
    {
        try {
            $user = $this->authSer->profile();
            return $this->sendResponse(StudentResource::make($user));
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}
