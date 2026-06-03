<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel item_furnitur: Melacak fisik barang
        Schema::create('item_furnitur', function (Blueprint $table) {
            $table->integer('id_item')->autoIncrement();
            $table->integer('id_furnitur');
            $table->string('kode_item', 100)->unique();
            $table->enum('status_item', ['Tersedia', 'Disewa', 'Rusak', 'Hilang'])->default('Tersedia');
            $table->timestamps();

            $table->foreign('id_furnitur')
                  ->references('id_furnitur')->on('furnitur')
                  ->onDelete('cascade');
        });

        // Tabel penyewa_furnitur: Melacak penyewaan item spesifik
        Schema::create('penyewa_furnitur', function (Blueprint $table) {
            $table->integer('id_penyewa_furnitur')->autoIncrement();
            $table->integer('id_booking')->nullable(); // nullable jika sewa tanpa booking
            $table->integer('id_item'); // FK ke item spesifik
            $table->integer('id_user');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai')->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_booking')
                  ->references('id_booking')->on('booking')
                  ->onDelete('cascade')
                  ->nullable();
            $table->foreign('id_item')
                  ->references('id_item')->on('item_furnitur')
                  ->onDelete('cascade');
            $table->foreign('id_user')
                  ->references('id_user')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyewa_furnitur');
        Schema::dropIfExists('item_furnitur');
    }
};
