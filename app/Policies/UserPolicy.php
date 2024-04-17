<?php

namespace App\Policies;

use App\Enums\User\UserStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function adminBlock(User $admin, User $user)
    {
        return $user->status === UserStatus::ACTIVE;
    }

    public function adminActive(User $admin, User $user)
    {
        return $user->status === UserStatus::BLOCK || $user->status === UserStatus::ADMIN_BLOCK;
    }
}
