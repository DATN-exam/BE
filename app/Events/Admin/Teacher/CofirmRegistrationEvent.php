<?php

namespace App\Events\Admin\Teacher;

use App\Models\TeacherRegistration;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CofirmRegistrationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message = 'Has 1 account cofirm teachers';
    public function __construct(protected TeacherRegistration $registration)
    {
        //
    }

    public function getUser(): User
    {
        return $this->registration->user;
    }

    public function getRegistration(): TeacherRegistration
    {
        return $this->registration;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('Admin'),
        ];
    }

    public function broadcastAs()
    {
        return 'teacher-cofirm';
    }
}
