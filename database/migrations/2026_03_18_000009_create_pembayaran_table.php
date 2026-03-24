<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->integer('id_pembayaran')->autoIncrement();
            $table->integer('id_tagihan');
            $table->string('order_id', 255)->unique();
            $table->string('snap_token', 255)->nullable();
            $table->string('transaction_id_gateway', 255)->nullable();
            $table->timestamp('tgl_bayar')->nullable();
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('metode_pembayaran', 50)->nullable();
            $table->enum('status_pembayaran', [
                'pending',
                'settlement',
                'expire',
                'cancel',
                'deny'
            ])->default('pending');

            $table->foreign('id_tagihan')->references('id_tagihan')->on('tagihan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
