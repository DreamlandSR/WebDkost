<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('pengiriman_status_logs')) {
            Schema::create('pengiriman_status_logs', function (Blueprint $table) {
                $table->id(); // Kolom id sebagai primary key auto-increment
                $table->integer('pengiriman_id')->nullable(false); // Diperbaiki dari 'perigriman_id'

                // Enum untuk status lama
                $table->enum('status_lama', [
                    'diproses',       // Diperbaiki dari 'Cliprosec'
                    'dikirim',        // Diperbaiki dari 'dblarm'
                    'dalam_perjalanan',
                    'sampai',
                    'gagal'
                ])->nullable(false);

                // Enum untuk status baru
                $table->enum('status_baru', [
                    'diproses',
                    'dikirim',
                    'dalam_perjalanan',
                    'sampai',
                    'gagal'
                ])->nullable(false);

                $table->timestamp('waktu_perubahan')->useCurrent(); // Diperbaiki dari 'waiku_perubahan'

                // Tambahkan indeks
                $table->index('waktu_perubahan');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_status_logs');
    }
};
