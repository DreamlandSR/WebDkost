<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review', function (Blueprint $table) {
            $table->integer('id_review')->autoIncrement();
            $table->integer('id_user');
            $table->integer('id_kamar');
            $table->integer('rating');
            $table->text('komentar')->nullable();
            $table->timestamp('tgl_review')->useCurrent();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_kamar')->references('id_kamar')->on('kamar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
