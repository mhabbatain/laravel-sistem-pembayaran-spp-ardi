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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('cascade');
            $table->foreignId('wali_murid_id')->constrained('wali_murid')->onDelete('cascade');
            $table->enum('metode_pembayaran', ['transfer', 'manual']);
            $table->date('tanggal_pembayaran');
            $table->decimal('jumlah_bayar', 10, 2);
            $table->string('bukti_pembayaran')->nullable();
            $table->string('nama_bank_pengirim')->nullable();
            $table->string('pemilik_rekening_pengirim')->nullable();
            $table->string('nomor_rekening_pengirim')->nullable();
            $table->foreignId('rekening_tujuan_id')->nullable()->constrained('rekening_sekolah')->onDelete('set null');
            $table->enum('status_konfirmasi', ['pending', 'dikonfirmasi', 'ditolak'])->default('pending');
            $table->date('tanggal_konfirmasi')->nullable();
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
