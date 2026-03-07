<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');

                // Untuk LONGBLOB di MySQL
                if (DB::connection()->getDriverName() === 'mysql') {
                    $table->binary('image_product')->nullable(false);
                    // Akan kita ubah ke LONGBLOB setelah tabel dibuat
                } else {
                    $table->binary('image_product')->nullable(false);
                }

                $table->boolean('is_main')->default(true);
                $table->timestamps();
            });

            // Untuk MySQL, ubah ke LONGBLOB setelah tabel dibuat
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE product_images MODIFY image_product LONGBLOB NOT NULL");
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
