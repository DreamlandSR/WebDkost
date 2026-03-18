<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendapatan', function (Blueprint $table) {
            $table->integer('id_pendapatan')->autoIncrement();
            $table->integer('id_pembayaran');
            $table->decimal('nominal', 15, 2);
            $table->date('tgl_diterima');

            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('pembayaran')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendapatan');
    }
};
