<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert WhatsApp settings into pengaturan table
        $settings = [
            [
                'key' => 'whatsapp_enabled',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'whatsapp_instance_name',
                'value' => 'spp-darul-jalal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'whatsapp_connected_phone',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'whatsapp_notify_tagihan',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'whatsapp_notify_pembayaran',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'whatsapp_notify_reminder',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('pengaturan')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pengaturan')->whereIn('key', [
            'whatsapp_enabled',
            'whatsapp_instance_name',
            'whatsapp_connected_phone',
            'whatsapp_notify_tagihan',
            'whatsapp_notify_pembayaran',
            'whatsapp_notify_reminder',
        ])->delete();
    }
};
