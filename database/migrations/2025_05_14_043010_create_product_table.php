<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('products')) {
        Schema::create('products', function (Blueprint $table) {
            
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2)->nullable();
            $table->integer('stok_id')->nullable();
            $table->enum('status', ['available', 'out_of_stock', 'hidden'])
                  ->default('available')
                  ->nullable();
            $table->float('rating')->default(0)->nullable();
            $table->integer('berat')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
}
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
