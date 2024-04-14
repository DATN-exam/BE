<?php

namespace App\Listeners\Site;

use App\Events\Site\UserRegister;
use App\Events\Site\UserRegisterEvent;
use App\Mail\Site\UserRegisterMail;
use App\Notifications\Site\UserRegisteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserRegisterListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisterEvent $event): void
    {
        Notification::send(
            $event->getUser(),
            new UserRegisteNotification($event->getLinkVerify())
        );
    }
}
