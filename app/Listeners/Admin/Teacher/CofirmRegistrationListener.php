<?php

namespace App\Listeners\Admin\Teacher;

use App\Notifications\ConfirmTeacherNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class CofirmRegistrationListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->getUser();
        $registration = $event->getRegistration();

        Notification::send(
            $user,
            new ConfirmTeacherNotification($registration)
        );
    }
}
