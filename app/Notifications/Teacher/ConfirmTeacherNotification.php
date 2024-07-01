<?php

namespace App\Notifications\Teacher;

use App\Enums\TeacherRegistration\TeacherRegistrationStatus;
use App\Models\TeacherRegistration;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

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
        return ['mail', 'database', 'broadcast'];
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

    public function broadcastOn()
    {
        return [
            new PrivateChannel("App.Models.User.{$this->registration->user_id}")
        ];
    }

    public function broadcastAs()
    {
        return 'user-notification';
    }

    private function mailAccept()
    {
        return (new MailMessage)
            ->line('Tài khoản của bạn đã được duyệt thành giáo viên');
    }

    private function mailDeny()
    {
        return (new MailMessage)
            ->line('Tài khoản của bạn đã bị từ chối đơn trở thành giáo viên');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "message" => $this->registration->status === TeacherRegistrationStatus::DENY ? "Đơn đăng kí giáo viên của bạn đã bị từ chối" : "Đơn đăng kí giáo viên của bạn đã được chấp nhận",
            "url" => $this->registration->status === TeacherRegistrationStatus::DENY ? null : config('define.url_teacher'),
        ];
    }
}
