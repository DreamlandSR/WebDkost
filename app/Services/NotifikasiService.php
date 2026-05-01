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
            'user_id'      => $user->id_user,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'tipe'         => $tipe,
            'sudah_dibaca' => false,
        ]);

        // 2. Kirim push via OneSignal (pakai id_user sebagai external_id)
        $this->oneSignal->kirimKeUser(
            externalUserId: (string) $user->id_user,
            judul: $judul,
            pesan: $pesan,
            data: [
                'tipe'    => $tipe,
                'notifId' => (string) $notif->id,
            ]
        );

        return $notif;
    }

    // ── Shortcut: reminder tagihan ────────────────────────────
    public function kirimReminderTagihan(User $user, string $tanggalJatuhTempo): Notifikasi
    {
        return $this->kirim(
            user: $user,
            judul: 'Reminder Tagihan',
            pesan: "Anda memiliki tagihan yang akan jatuh tempo pada tanggal {$tanggalJatuhTempo}, Segera lakukan perpanjangan sewa",
            tipe: 'tagihan',
        );
    }

    // ── Shortcut: status keluhan ──────────────────────────────
    public function kirimStatusKeluhan(User $user, string $statusPesan = ''): Notifikasi
    {
        $pesan = $statusPesan ?: 'Keluhan anda telah diproses oleh admin dan akan segera dilakukan tindakan';
        return $this->kirim(
            user: $user,
            judul: 'Status Keluhan',
            pesan: $pesan,
            tipe: 'keluhan',
        );
    }

    // ── Shortcut: booking dikonfirmasi ────────────────────────
    public function kirimKonfirmasiBooking(User $user, string $namaKamar): Notifikasi
    {
        return $this->kirim(
            user: $user,
            judul: 'Booking Dikonfirmasi',
            pesan: "Booking kamar {$namaKamar} anda telah dikonfirmasi oleh admin",
            tipe: 'umum',
        );
    }
}