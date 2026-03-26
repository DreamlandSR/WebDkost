<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->integer('id_pengeluaran')->autoIncrement();
            $table->string('kategori', 100);
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->date('tgl_transaksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
