<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Site\Auth\ConfirmForgotPassRequest;
use App\Http\Requests\Site\Auth\ForgotPassRequest;
use App\Http\Requests\Site\Auth\LoginRequest;
use App\Http\Requests\Site\Auth\RegisterRequest;
use App\Http\Requests\Site\Auth\UpdateRequest;
use App\Http\Resources\Site\StudentResource;
use App\Services\Admin\Cron\ExamCronService;
use App\Services\Site\AuthService;
use App\Services\Site\GoogleAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends BaseApiController
{
    public function __construct(
        protected AuthService $authSer,
        protected GoogleAuthService $googleAuthSer
    ) {
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

    public function update(UpdateRequest $rq)
    {
        try {
            $this->authSer->setRequestValidated($rq)->update();
            return $this->sendResponse([
                'message' => __('alert.update.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
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

    public function register(RegisterRequest $rq)
    {
        try {
            $user = $this->authSer->setRequestValidated($rq)->register();
            return $this->sendResponse(StudentResource::make($user));
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function verify(Request $rq)
    {
        try {
            $this->authSer->handleVerify($rq->all());
            return $this->sendResponse([
                'message' => __('alert.auth.verify.success'),
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError(__('alert.auth.verify.failed'));
        }
    }

    public function getLoginGoogleUrl()
    {
        try {
            $data = $this->googleAuthSer->getUrlLogin();
            return $this->sendResponse([
                'url' => $data,
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function loginGoogleCallback(Request $rq)
    {
        try {
            $data = $this->googleAuthSer->setRequest($rq)->loginCallback();
            return $this->sendResponse($data);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function forgotPass(ForgotPassRequest $rq)
    {
        try {
            $this->authSer->setRequestValidated($rq)->forgotPass();
            return $this->sendResponse([
                'message' => 'Vui lòng kiểm tra email để đặt lại mật khẩu',
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function confirmForgotPass(ConfirmForgotPassRequest $rq)
    {
        try {
            $this->authSer->setRequestValidated($rq)->confirmForgotPass();
            return $this->sendResponse([
                'message' => 'Bạn đã đặt lại mật khẩu thành công',
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}
