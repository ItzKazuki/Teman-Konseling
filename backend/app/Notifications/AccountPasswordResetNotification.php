<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountPasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public bool $isByAdmin = true,
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
        // Logika penentuan title dan message
        if ($this->isByAdmin) {
            $title = 'Password Direset Admin';
            $message = 'Password akun kamu telah direset oleh admin demi alasan keamanan atau permintaan bantuan. Silakan login kembali.';
        } else {
            $title = 'Password Berhasil Diubah';
            $message = 'Kamu baru saja mengubah password akun. Jika ini bukan kamu, segera hubungi admin sekolah.';
        }

        return [
            'title' => $title,
            'message' => $message,
            'type' => 'account_password_reset',
            'action_url' => '/profile',    // Arahkan ke profil setelah login
        ];
    }
}
