<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->integer('id_tagihan')->autoIncrement();
            $table->integer('id_booking');
            $table->date('periode_bulan');
            $table->decimal('nominal_dasar', 15, 2);
            $table->decimal('nominal_denda', 15, 2)->default(0);
            $table->decimal('total_tagihan', 15, 2);
            $table->date('tgl_jatuh_tempo');
            $table->enum('status_tagihan', ['belum_bayar', 'lunas', 'terlambat'])->default('belum_bayar');

            $table->foreign('id_booking')->references('id_booking')->on('booking')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
