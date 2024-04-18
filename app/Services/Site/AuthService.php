<?php

namespace App\Services\Site;

use App\Enums\User\UserStatus;
use App\Events\Site\UserRegisterEvent;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends BaseService
{
    public function __construct(protected UserRepositoryInterface $userRepo)
    {
        //
    }

    public function login(): array
    {
        $credentials = $this->data;
        if (!$token = auth('api')->attempt($credentials)) {
            throw new \Exception(__('alert.auth.login.failed'));
        }
        $this->checkStatus(auth('api')->user()->status);
        return $this->respondWithToken($token);
    }

    public function loginWithgoogle($user)
    {
        if (!$token = auth('api')->login($user)) {
            throw new \Exception(__('alert.auth.login.failed'));
        }
        $this->checkStatus(auth('api')->user()->status);
        return $this->respondWithToken($token);
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function profile(): User
    {
        return auth('api')->user();
    }

    private function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    private function checkStatus($status): void
    {
        switch ($status) {
            case UserStatus::BLOCK:
                throw new \Exception(__('alert.auth.login.blocked'));
                return;
            case UserStatus::ADMIN_BLOCK:
                throw new \Exception(__('alert.auth.login.blocked'));
                return;
            case UserStatus::WAIT_VERIFY:
                throw new \Exception(__('alert.auth.login.wait_verify'));
                return;
            default:
                return;
        }
    }

    public function register()
    {
        $tokenVerify = Str::random(40);
        $dataCreate = [...$this->data, 'token_verify' => $tokenVerify];
        $user = $this->userRepo->create($dataCreate);
        // $linkVerify = route('auth.verify', ['token' => $this->createVerifyToken($user)]);
        $linkVerify = config('define.url_verify') . "?token=" . $this->createVerifyToken($user);
        event(new UserRegisterEvent($user, $linkVerify));
        return $user;
    }

    private function createVerifyToken(User $user)
    {
        $data = [
            'user_id' => $user->id,
            'random' => rand() . time(),
            'token' => $user->token_verify,
        ];
        $token = JWTAuth::getJWTProvider()->encode($data);
        return $token;
    }

    public function handleVerify($rq)
    {
        $user = $this->checkVerifyToken($rq['token']);
        if ($user) {
            return $this->verify($user);
        }
        throw new \Exception();
    }

    private function checkVerifyToken($token)
    {
        $decode =  JWTAuth::getJWTProvider()->decode($token);
        return $this->userRepo->findUserVerify($decode['token'], $decode['user_id']);
    }

    public function verify(User $user)
    {
        $this->userRepo->update($user, [
            'status' => UserStatus::ACTIVE,
            'token_verify' => null,
        ]);
        return true;
    }
}
