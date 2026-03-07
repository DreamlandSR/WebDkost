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
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id(); // Ini sama dengan `id` int AUTO_INCREMENT
                $table->integer('product_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('rating')->nullable();
                $table->text('komentar')->nullable(); // Perhatikan typo di struktur asli 'konnertar'
                $table->string('updated_at', 255)->nullable();
                $table->timestamp('created_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP

                // Jika ingin menambahkan indeks
                $table->index('product_id');
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
