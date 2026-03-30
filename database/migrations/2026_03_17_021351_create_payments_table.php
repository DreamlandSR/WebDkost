<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('order_id')->unique();
        $table->bigInteger('amount');
        $table->string('snap_token')->nullable();
        $table->string('status')->default('pending'); // pending, paid, failed, expired
        $table->string('payment_type')->nullable();   // gopay, bank_transfer, dll
        $table->string('transaction_id')->nullable(); // ID dari Midtrans
        $table->text('notes')->nullable();            // catatan, misal: "Sewa bulan Januari"
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
