<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiService
{
    public function __construct(private FcmService $fcm) {}

    // ── Kirim notifikasi ke 1 user ────────────────────────────
    public function kirim(
        User   $user,
        string $judul,
        string $pesan,
        string $tipe = 'umum'
    ): Notifikasi {
        // 1. Simpan ke database dulu
        $notif = Notifikasi::create([
            'user_id'      => $user->id,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'tipe'         => $tipe,
            'sudah_dibaca' => false,
        ]);

        // 2. Kirim push notification kalau user punya FCM token
        if ($user->fcm_token) {
            $this->fcm->kirimKeUser(
                fcmToken: $user->fcm_token,
                judul: $judul,
                pesan: $pesan,
                data: [
                    'tipe'       => $tipe,
                    'notifId'    => (string) $notif->id,
                ]
            );
        }

        return $notif;
    }

    // ── Shortcut: notifikasi tagihan ──────────────────────────
    public function kirimReminderTagihan(User $user, string $tanggalJatuhTempo): Notifikasi
    {
        return $this->kirim(
            user: $user,
            judul: 'Reminder Tagihan',
            pesan: "Anda memiliki tagihan yang akan jatuh tempo pada tanggal {$tanggalJatuhTempo}, Segera lakukan perpanjangan sewa",
            tipe: 'tagihan',
        );
    }

    // ── Shortcut: notifikasi keluhan diproses ─────────────────
    public function kirimStatusKeluhan(User $user, string $statusPesan = ''): Notifikasi
    {
        $pesan = $statusPesan ?: 'keluhan anda telah diproses oleh admin dan akan segera dilakukan tindakan';
        return $this->kirim(
            user: $user,
            judul: 'Status Keluhan',
            pesan: $pesan,
            tipe: 'keluhan',
        );
    }
}