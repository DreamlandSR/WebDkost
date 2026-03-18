<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas_kamar', function (Blueprint $table) {
            $table->integer('id_fasilitas')->autoIncrement();
            $table->integer('id_kamar');
            $table->string('nama_fasilitas', 255);
            $table->text('deskripsi_fasilitas')->nullable();

            $table->foreign('id_kamar')->references('id_kamar')->on('kamar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas_kamar');
    }
};
