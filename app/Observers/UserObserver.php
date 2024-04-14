<?php

namespace App\Observers;

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    public function creating(User $user)
    {
        $user->role = $user->role ? $user->role : UserRole::STUDENT;
        $user->status = $user->status ? $user->status : UserStatus::WAIT_VERIFY;
    }
}
