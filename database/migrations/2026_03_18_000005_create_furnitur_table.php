<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('furnitur', function (Blueprint $table) {
            $table->integer('id_furnitur')->autoIncrement();
            $table->string('nama_furnitur', 255);
            $table->integer('jumlah');
            $table->decimal('harga_sewa_tambahan', 15, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('furnitur');
    }
};
