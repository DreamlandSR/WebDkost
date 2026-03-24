<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Users ──────────────────────────────────────────
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->enum('role', ['admin', 'penyewa'])->default('penyewa');
            $table->timestamp('created_at')->useCurrent();
        });

        // ── Kamar ───────────────────────────────────────────
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->string('nomor_kamar', 20)->unique();
            $table->enum('tipe_kamar', ['biasa', 'sedang', 'mewah']);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_per_bulan', 10, 2);
            $table->enum('status_kamar', ['tersedia', 'terisi', 'maintenance'])->default('tersedia');
        });

        // ── Galeri Kamar ────────────────────────────────────
        Schema::create('galeri_kamar', function (Blueprint $table) {
            $table->id('id_foto');
            $table->foreignId('id_kamar')->constrained('kamar', 'id_kamar')->cascadeOnDelete();
            $table->string('url_foto');
            $table->tinyInteger('is_main')->default(0);
        });

        // ── Fasilitas Kamar ─────────────────────────────────
        Schema::create('fasilitas_kamar', function (Blueprint $table) {
            $table->id('id_fasilitas');
            $table->foreignId('id_kamar')->constrained('kamar', 'id_kamar')->cascadeOnDelete();
            $table->string('nama_fasilitas');
            $table->text('deskripsi_fasilitas')->nullable();
        });

        // ── Furnitur ────────────────────────────────────────
        Schema::create('furnitur', function (Blueprint $table) {
            $table->id('id_furnitur');
            $table->string('nama_furnitur');
            $table->integer('jumlah')->default(0);
            $table->decimal('harga_sewa_tambahan', 10, 2)->default(0);
        });

        // ── Booking ─────────────────────────────────────────
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->foreignId('id_kamar')->constrained('kamar', 'id_kamar');
            $table->date('tgl_booking');
            $table->integer('durasi_sewa_bulan');
            $table->date('tgl_mulai_sewa');
            $table->date('tgl_akhir_sewa');
            $table->decimal('total_biaya_bulanan', 12, 2);
            $table->enum('status_booking', ['menunggu_pembayaran','aktif','selesai','batal','expired'])
                  ->default('menunggu_pembayaran');
        });

        // ── Booking Detail Furnitur ─────────────────────────
        Schema::create('booking_detail_furnitur', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_booking')->constrained('booking', 'id_booking')->cascadeOnDelete();
            $table->foreignId('id_furnitur')->constrained('furnitur', 'id_furnitur');
            $table->integer('jumlah');
        });

        // ── Tagihan ─────────────────────────────────────────
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id('id_tagihan');
            $table->foreignId('id_booking')->constrained('booking', 'id_booking')->cascadeOnDelete();
            $table->date('periode_bulan');
            $table->decimal('nominal_dasar', 12, 2);
            $table->decimal('nominal_denda', 12, 2)->default(0);
            $table->decimal('total_tagihan', 12, 2);
            $table->date('tgl_jatuh_tempo');
            $table->enum('status_tagihan', ['belum_bayar','lunas','terlambat'])->default('belum_bayar');
        });

        // ── Pembayaran ──────────────────────────────────────
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_tagihan')->constrained('tagihan', 'id_tagihan');
            $table->string('order_id')->unique();
            $table->text('snap_token')->nullable();
            $table->string('transaction_id_gateway')->nullable();
            $table->timestamp('tgl_bayar')->nullable();
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['pending','settlement','expire','cancel','deny'])
                  ->default('pending');
        });

        // ── Keluhan ─────────────────────────────────────────
        Schema::create('keluhan', function (Blueprint $table) {
            $table->id('id_keluhan');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->foreignId('id_kamar')->constrained('kamar', 'id_kamar');
            $table->text('deskripsi_masalah');
            $table->string('foto_bukti')->nullable();
            $table->timestamp('tgl_lapor')->useCurrent();
            $table->enum('status_keluhan', ['pending','diproses','selesai'])->default('pending');
        });

        // ── Review ──────────────────────────────────────────
        Schema::create('review', function (Blueprint $table) {
            $table->id('id_review');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->foreignId('id_kamar')->constrained('kamar', 'id_kamar');
            $table->integer('rating');
            $table->text('komentar')->nullable();
            $table->timestamp('tgl_review')->useCurrent();
            $table->unique(['id_user', 'id_kamar']);
        });

        // ── Pengeluaran ─────────────────────────────────────
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->string('kategori');
            $table->decimal('nominal', 12, 2);
            $table->text('keterangan')->nullable();
            $table->date('tgl_transaksi');
        });

        // ── Pendapatan ──────────────────────────────────────
        Schema::create('pendapatan', function (Blueprint $table) {
            $table->id('id_pendapatan');
            $table->foreignId('id_pembayaran')->constrained('pembayaran', 'id_pembayaran');
            $table->decimal('nominal', 12, 2);
            $table->date('tgl_diterima');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendapatan');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('review');
        Schema::dropIfExists('keluhan');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('tagihan');
        Schema::dropIfExists('booking_detail_furnitur');
        Schema::dropIfExists('booking');
        Schema::dropIfExists('furnitur');
        Schema::dropIfExists('fasilitas_kamar');
        Schema::dropIfExists('galeri_kamar');
        Schema::dropIfExists('kamar');
        Schema::dropIfExists('users');
    }
};