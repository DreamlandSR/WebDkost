<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->string('no_hp', 20)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('otp', 6)->nullable();
            $table->dateTime('otp_expiry')->nullable();
            $table->enum('role', ['admin', 'user'])->nullable();
            $table->string('avatar', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
}

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
