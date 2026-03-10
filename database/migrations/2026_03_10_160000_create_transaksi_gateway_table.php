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
        Schema::create('transaksi_gateway', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayaran')->onDelete('cascade');
            $table->string('kode_transaksi')->unique(); // TRX-XXXXXX
            $table->foreignId('metode_pembayaran_id')->constrained('metode_pembayaran')->onDelete('cascade');
            $table->decimal('jumlah', 12, 2);
            $table->enum('status', ['pending', 'processing', 'authorized', 'settled', 'failed', 'expired'])->default('pending');
            $table->json('gateway_response')->nullable(); // Simulated bank/provider response
            $table->timestamp('authorized_at')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('token')->unique(); // Unique token for gateway page access
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_gateway');
    }
};
