<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiService
{
    public function __construct(private OneSignalService $oneSignal) {}

    // ── Kirim notifikasi ke 1 user ────────────────────────────
    public function kirim(
        User   $user,
        string $judul,
        string $pesan,
        string $tipe = 'umum'
    ): Notifikasi {
        // 1. Simpan ke database
        $notif = Notifikasi::create([
            'user_id'      => $user->id,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'tipe'         => $tipe,
            'sudah_dibaca' => false,
        ]);

        // 2. Kirim push notification kalau user punya OneSignal Player ID
        if ($user->onesignal_player_id) {
            $this->oneSignal->kirimKeUser(
                playerId: $user->onesignal_player_id,
                judul: $judul,
                pesan: $pesan,
                data: [
                    'tipe'    => $tipe,
                    'notifId' => (string) $notif->id,
                ]
            );
        }

        return $notif;
    }

    // ── Shortcut: reminder tagihan ────────────────────────────
    public function kirimReminderTagihan(User $user, string $tanggalJatuhTempo): Notifikasi
    {
        return $this->kirim(
            user: $user,
            judul: 'Reminder Tagihan',
            pesan: "Anda memiliki tagihan yang akan jatuh tempo pada tanggal {$tanggalJatuhTempo}, segera lakukan perpanjangan sewa.",
            tipe: 'tagihan',
        );
    }

    // ── Shortcut: status keluhan ──────────────────────────────
    public function kirimStatusKeluhan(User $user, string $statusPesan = ''): Notifikasi
    {
        $pesan = $statusPesan ?: 'Keluhan Anda telah diproses oleh admin dan akan segera dilakukan tindakan.';

        return $this->kirim(
            user: $user,
            judul: 'Status Keluhan',
            pesan: $pesan,
            tipe: 'keluhan',
        );
    }
}