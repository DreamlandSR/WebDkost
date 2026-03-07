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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id(); // corresponds to 'id' column with AUTO_INCREMENT
                $table->integer('user_id')->nullable(false); // not null
                $table->dateTime('waktu_order')->nullable()->useCurrent(); // nullable with CURRENT_TIMESTAMP default
                $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'cancelled'])->default('pending');
                $table->decimal('total_harga', 10, 2)->nullable(false); // decimal(10,2) not null
                $table->text('alamat_pemesanan')->nullable(); // text nullable
                $table->string('metode_pengiriman', 100)->nullable(); // varchar(100) nullable
                $table->string('notes', 255)->nullable(); // varchar(255) nullable
                $table->timestamp('created_at')->nullable()->useCurrent(); // timestamp with CURRENT_TIMESTAMP default

                // If you need to add foreign key for user_id
                // $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
