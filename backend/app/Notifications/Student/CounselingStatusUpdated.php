<?php

namespace App\Notifications\Student;

use App\Models\RequestCounseling;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CounselingStatusUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public RequestCounseling $requestCounseling,
        public string $status
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title(),
            'message' => $this->message(),
            'type' => 'counseling_scheduled',
            'action_url' => '/chats',
        ];
    }

    protected function title(): string
    {
        return match ($this->status) {
            'rejected' => 'Pengajuan Ditolak',
            'confirmed' => 'Jadwal Dikonfirmasi',
            'completed' => 'Konseling Selesai',
            default => 'Status Konseling Diperbarui',
        };
    }

    protected function message(): string
    {
        $counselingTitle = '"'.$this->requestCounseling->title.'"';

        return match ($this->status) {
            'rejected' => "Pengajuan konseling $counselingTitle ditolak oleh Guru BK.",
            'confirmed' => "Jadwal untuk sesi $counselingTitle telah dikonfirmasi.",
            'completed' => "Sesi konseling $counselingTitle telah selesai. Terima kasih sudah bercerita!",
            default => "Status konseling $counselingTitle kamu telah diperbarui.",
        };
    }
}
