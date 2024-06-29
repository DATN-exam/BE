<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPassNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $url;
    /**
     * Create a new notification instance.
     */
    public function __construct(protected $token)
    {
        $this->url = config('define.url_forgot_pass') . '?token=' . $token;
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
        return (new MailMessage)
            ->line('Bạn vừa yêu cầu đổi lại mật khẩu')
            ->action('Vui lòng bấm vào link sau để đổi lại mật khẩu', $this->url)
            ->line('Vui lòng không cung cấp link cho bất kì ai.');
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
