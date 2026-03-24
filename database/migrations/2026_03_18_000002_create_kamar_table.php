<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->integer('id_kamar')->autoIncrement();
            $table->string('nomor_kamar', 50)->unique();
            $table->enum('tipe_kamar', ['biasa', 'sedang', 'mewah']);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_per_bulan', 15, 2);
            $table->enum('status_kamar', ['tersedia', 'terisi', 'maintenance'])->default('tersedia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
