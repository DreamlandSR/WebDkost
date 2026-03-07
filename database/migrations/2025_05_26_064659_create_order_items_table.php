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
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id(); // corresponds to 'id' column with AUTO_INCREMENT
                $table->unsignedBigInteger('order_id')->nullable(false); // not null
                $table->unsignedBigInteger('product_id')->nullable(false); // not null
                $table->integer('kuantitas')->nullable(false); // int not null
                $table->decimal('harga', 10, 2)->nullable(false); // decimal(10,2) not null
                $table->decimal('subtotal', 10, 2)->nullable()->storedAs('kuantitas * harga'); // generated column

                // Foreign keys (uncomment if needed)
                // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
