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
        if (!Schema::hasTable('cart')) {
            Schema::create('cart', function (Blueprint $table) {
                $table->id(); // Kolom id sebagai primary key auto-increment
                $table->integer('user_id')->nullable(false); // Tidak boleh null
                $table->integer('product_id')->nullable(false); // Tidak boleh null
                $table->integer('quantity')->default(1)->nullable(false); // Default 1, tidak boleh null
                $table->timestamp('added_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

                // Tambahkan indeks untuk meningkatkan performa query
                $table->index('user_id');
                $table->index('product_id');

                // Jika ingin memastikan kombinasi user_id dan product_id unik
                // $table->unique(['user_id', 'product_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
