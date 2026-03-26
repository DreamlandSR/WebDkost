<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_kamar', function (Blueprint $table) {
            $table->integer('id_foto')->autoIncrement();
            $table->integer('id_kamar');
            $table->longText('url_foto');
            $table->tinyInteger('is_main')->default(0);

            $table->foreign('id_kamar')->references('id_kamar')->on('kamar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_kamar');
    }
};
