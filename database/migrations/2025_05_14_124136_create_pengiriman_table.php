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
        if (!Schema::hasTable('pengiriman')) {
            Schema::create('pengiriman', function (Blueprint $table) {
                $table->id(); // corresponds to 'id' column with AUTO_INCREMENT
                $table->unsignedBigInteger('order_id')->nullable(false); // int not null
                $table->enum('status_pengiriman', ['diproses', 'dikirim', 'dalam_perjalanan', 'sampai', 'gagal'])
                      ->default('diproses')
                      ->nullable();
                $table->string('nomor_resi', 100)->nullable(); // varchar(100) nullable
                $table->string('jasa_kurir', 100)->nullable(); // varchar(100) nullable
                $table->date('tanggal_dikirim')->nullable(); // date nullable
                $table->timestamp('created_at')->useCurrent(); // timestamp with CURRENT_TIMESTAMP default
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
