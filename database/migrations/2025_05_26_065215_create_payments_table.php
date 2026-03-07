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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id(); // corresponds to 'id' column with AUTO_INCREMENT
                $table->unsignedBigInteger('order_id')->nullable(false); // not null
                $table->string('metode_pembayaran', 100)->nullable(); // varchar(100) nullable
                $table->enum('status_pembayaran', ['pending', 'completed', 'failed', 'refunded'])
                    ->default('pending')
                    ->nullable(); // enum with default 'pending'
                $table->dateTime('waktu_pembayaran')->nullable(); // datetime nullable

                // Foreign key (uncomment if needed)
                // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

                // Optional: Add timestamps if needed
                // $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
