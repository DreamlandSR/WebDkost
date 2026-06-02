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
        if (Schema::hasTable('booking')) {
            Schema::table('booking', function (Blueprint $table) {
                if (Schema::hasColumn('booking', 'expired_at')) {
                    $table->dateTime('expired_at')->nullable()->change();
                } else {
                    $table->dateTime('expired_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('booking')) {
            Schema::table('booking', function (Blueprint $table) {
                if (Schema::hasColumn('booking', 'expired_at')) {
                    $table->date('expired_at')->nullable()->change();
                }
            });
        }
    }
};