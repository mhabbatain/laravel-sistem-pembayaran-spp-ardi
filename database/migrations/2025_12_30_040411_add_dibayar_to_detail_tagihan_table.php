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
        Schema::table('detail_tagihan', function (Blueprint $table) {
            $table->decimal('jumlah_dibayar', 10, 2)->default(0)->after('jumlah');
            $table->boolean('is_selected')->default(true)->after('jumlah_dibayar')->comment('Apakah komponen ini dipilih untuk dibayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_tagihan', function (Blueprint $table) {
            $table->dropColumn(['jumlah_dibayar', 'is_selected']);
        });
    }
};
