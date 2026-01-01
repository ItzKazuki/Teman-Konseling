<?php

namespace App\Notifications\Student;

use App\Models\RequestCounseling;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestCounselingCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public RequestCounseling $requestCounseling,
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
            'type' => 'counseling_request_created',
            'title' => 'Permohonan Konseling Berhasil Dibuat',
            'message' => 'Permohonan konseling kamu sudah berhasil dibuat. Silakan pilih tanggal dan metode konseling untuk melanjutkan proses.',
            'action_url' => '/chats/counselors?requestId='.$this->requestCounseling->id,
            'request_id' => $this->requestCounseling->id,
        ];
    }
}
