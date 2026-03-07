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
        if (!Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id(); // Kolom id sebagai primary key auto-increment
                $table->integer('user_id')->nullable(false);
                $table->string('nama_lengkap', 100)->nullable(false);
                $table->string('nomor_hp', 20)->nullable(false); // Diperbaiki dari 'nomor_lip'
                $table->text('provinsi')->nullable(false); // Diperbaiki dari 'provinci'
                $table->string('kota', 100)->nullable(false);
                $table->string('kecamatan', 100)->nullable(false);
                $table->integer('kode_pos')->nullable(false); // Diperbaiki dari 'kods_pos'
                $table->string('alamat_lengkap', 255)->nullable();
                $table->timestamp('created_at')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP

                // Tambahkan indeks
                $table->index('user_id');
                $table->index('kode_pos');

                // Foreign key constraint (jika diperlukan)
                // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
