<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReminderPembayaran extends Notification
{
    public function __construct(public object $tagihan) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $total   = 'Rp ' . number_format($this->tagihan->total_tagihan, 0, ',', '.');
        $tgl     = \Carbon\Carbon::parse($this->tagihan->tgl_jatuh_tempo)
                    ->locale('id')->isoFormat('D MMMM Y');

        return (new MailMessage)
            ->subject('🔔 Reminder Pembayaran Kos - ' . $this->tagihan->periode_bulan)
            ->greeting('Halo ' . $notifiable->nama . '!')
            ->line('Ini adalah pengingat tagihan sewa kos kamu.')
            ->line('---')
            ->line('🏠 **Kamar:** ' . $this->tagihan->nomor_kamar)
            ->line('📅 **Periode:** ' . $this->tagihan->periode_bulan)
            ->line('💰 **Total Tagihan:** ' . $total)
            ->line('⏰ **Jatuh Tempo:** ' . $tgl)
            ->line('---')
            ->action('Lihat Tagihan', url('/tagihan'))
            ->line('Mohon segera lakukan pembayaran sebelum jatuh tempo.')
            ->line('Terima kasih! 🙏')
            ->salutation('Salam, Tim Kost App');
    }
}
