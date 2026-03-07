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
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id(); // Kolom id sebagai primary key auto-increment
                $table->integer('user_id')->nullable();
                $table->integer('product_id')->nullable();
                $table->timestamp('created_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP

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
        Schema::dropIfExists('favorites');
    }
};
