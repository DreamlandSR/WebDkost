<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->integer('id_booking')->autoIncrement();
            $table->integer('id_user');
            $table->integer('id_kamar');
            $table->date('tgl_booking');
            $table->integer('durasi_sewa_bulan');
            $table->date('tgl_mulai_sewa')->nullable();
            $table->date('tgl_akhir_sewa')->nullable();
            $table->decimal('total_biaya_bulanan', 15, 2)->nullable();
            $table->enum('status_booking', [
                'menunggu_pembayaran',
                'aktif',
                'selesai',
                'batal',
                'expired'
            ])->default('menunggu_pembayaran');

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_kamar')->references('id_kamar')->on('kamar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
