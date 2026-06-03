<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'sudah_dibaca',
        'dibaca_at',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
        'dibaca_at'    => 'datetime',
    ];

    // ── Relasi ────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scope ─────────────────────────────────────────────────
    public function scopeBelumDibaca($query)
    {
        return $query->where('sudah_dibaca', false);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}