<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->Integer('user_id');
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['tagihan', 'keluhan', 'umum'])->default('umum');
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'sudah_dibaca']);
        });

        // Tambah kolom fcm_token di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('fcm_token')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
    }
};