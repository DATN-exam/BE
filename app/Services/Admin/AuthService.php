<?php

namespace App\Services\Admin;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\User;
use App\Services\BaseService;

class AuthService extends BaseService
{
    // public function __construct(protected UserRepositoryInterface $userRepo)
    // {
    //     //
    // }

    public function login(): array
    {
        $credentials = $this->data;
        $credentials = [...$credentials, 'role' => UserRole::ADMIN->value];
        if (!$token = auth('api')->attempt($credentials)) {
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
            default:
                return;
        }
    }
}
