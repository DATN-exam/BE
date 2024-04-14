<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Auth\LoginRequest;
use App\Http\Requests\Site\Auth\RegisterRequest;
use App\Http\Resources\Site\UserResource;
use App\Services\Site\AuthService;
use Illuminate\Http\Request;
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
            $data = $this->authSer->setRequest($rq)->login();
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
            return $this->sendResponse(UserResource::make($user));
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function register(RegisterRequest $rq)
    {
        try {
            $user = $this->authSer->setRequest($rq)->register();
            return $this->sendResponse(UserResource::make($user));
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function verify(Request $rq)
    {
        try {
            $this->authSer->verify($rq->all());
            return 1;
        } catch (Throwable $e) {
            Log::error($e);
            dd(3);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}
