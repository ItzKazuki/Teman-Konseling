<?php

namespace App\Notifications\Student;

use App\Models\ScheduleCounseling;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleCounselingCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ScheduleCounseling $schedule,
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
            'schedule_id' => $this->schedule->id,
            'title' => 'Jadwal Konseling Terbuat!',
            'message' => 'Halo! Jadwal konseling kamu sudah berhasil diajukan untuk tanggal '.
                          \Carbon\Carbon::parse($this->schedule->schedule_date)->format('d M Y').
                          ' pukul '.$this->schedule->time_slot.'. Tunggu konfirmasi dari Guru BK ya!',
            'type' => 'counseling_scheduled',
            'action_url' => '/chats',
        ];
    }
}
