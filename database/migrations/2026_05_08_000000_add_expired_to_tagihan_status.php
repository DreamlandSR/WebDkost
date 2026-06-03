<?php
// database/migrations/2026_05_08_000000_add_expired_to_tagihan_status.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddExpiredToTagihanStatus extends Migration
{
    public function up()
    {
        // Untuk MySQL
        DB::statement("ALTER TABLE tagihan MODIFY COLUMN status_tagihan ENUM('belum_bayar', 'lunas', 'dibatalkan', 'expired') NOT NULL DEFAULT 'belum_bayar'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE tagihan MODIFY COLUMN status_tagihan ENUM('belum_bayar', 'lunas', 'dibatalkan') NOT NULL DEFAULT 'belum_bayar'");
    }
}