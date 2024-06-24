<?php

namespace App\Notifications\Student;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;


class EndExamNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $urlExam = '';
    protected $message = '';

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Exam $exam, protected User $student)
    {
        $this->urlExam = Str::replace(':id_classroom', $exam->classroom->id, config('define.url_exam'));
        $this->urlExam = Str::replace(':id_exam', $exam->id, $this->urlExam);
        $this->message = "Cuộc thi {$exam->name} đã kết thúc vào lúc {$exam->start_date}";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("Cuộc thi {$this->exam->name} đã được kết thúc")
            ->action('Bấm vào đây để xem chi tiết', $this->urlExam)
            ->line('Toanf!');
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel("App.Models.User.{$this->student->id}")
        ];
    }

    public function broadcastAs()
    {
        return 'user-notification';
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
