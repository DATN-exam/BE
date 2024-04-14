<?php

namespace App\Notifications\Teacher;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
use App\Models\TeacherRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmTeacherNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected TeacherRegistration $registration)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->registration->status === TeacherRegistrationStatus::DENY) {
            return $this->mailDeny();
        };
        return $this->mailAccept();
    }

    private function mailAccept()
    {
        return (new MailMessage)
            ->line('accept');
    }

    private function mailDeny()
    {
        return (new MailMessage)
            ->line('deny');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
