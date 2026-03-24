<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluhan', function (Blueprint $table) {
            $table->integer('id_keluhan')->autoIncrement();
            $table->integer('id_user');
            $table->integer('id_kamar');
            $table->text('deskripsi_masalah');
            $table->string('foto_bukti', 255)->nullable();
            $table->timestamp('tgl_lapor')->useCurrent();
            $table->enum('status_keluhan', ['pending', 'diproses', 'selesai'])->default('pending');

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_kamar')->references('id_kamar')->on('kamar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluhan');
    }
};
