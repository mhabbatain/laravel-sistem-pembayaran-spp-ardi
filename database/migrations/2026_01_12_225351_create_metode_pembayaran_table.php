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
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Bank BCA, Mandiri, DANA, etc.
            $table->string('kode')->unique(); // bca, mandiri, dana, etc.
            $table->enum('kategori', ['bank_transfer', 'e_wallet', 'kartu', 'qris']); 
            $table->string('logo')->nullable(); // path to logo image
            $table->string('nomor_rekening')->nullable(); // for bank transfer
            $table->string('nama_pemilik')->nullable(); // account holder name
            $table->text('instruksi')->nullable(); // payment instructions
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metode_pembayaran');
    }
};
