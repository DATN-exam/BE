<?php

namespace App\Notifications\Student;

use App\Models\ExamHistory;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class AutoSubmitNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $urlExam = '';
    protected $message = '';
    /**
     * Create a new notification instance.
     */
    public function __construct(protected ExamHistory $examHistory)
    {
        $this->urlExam = Str::replace(':id_classroom', $examHistory->exam->classroom->id, config('define.url_exam'));
        $this->urlExam = Str::replace(':id_exam', $examHistory->exam->id, $this->urlExam);
        $this->message = "Bài thi của cuộc thi {$this->examHistory->exam->name} đã được tự động nộp do hết giờ làm bài.";
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

    public function broadcastOn()
    {
        return [
            new PrivateChannel("App.Models.User.{$this->examHistory->student->id}")
        ];
    }

    public function broadcastAs()
    {
        return 'user-notification';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Nộp bài tự động!')
            ->line($this->message)
            ->action('Xem chi tiết', url($this->urlExam))
            ->line('Toanf exam');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "message" => $this->message,
            "url" => $this->urlExam,
        ];
    }
}
