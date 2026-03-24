<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_detail_furnitur', function (Blueprint $table) {
            $table->integer('id_detail')->autoIncrement();
            $table->integer('id_booking');
            $table->integer('id_furnitur');
            $table->integer('jumlah');

            $table->foreign('id_booking')->references('id_booking')->on('booking')->onDelete('cascade');
            $table->foreign('id_furnitur')->references('id_furnitur')->on('furnitur')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_detail_furnitur');
    }
};
