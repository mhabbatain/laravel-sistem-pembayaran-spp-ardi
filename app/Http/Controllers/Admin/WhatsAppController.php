<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected WhatsAppService $whatsApp;

    public function __construct(WhatsAppService $whatsApp)
    {
        $this->whatsApp = $whatsApp;
    }

    /**
     * Display WhatsApp settings page
     */
    public function index()
    {
        $settings = [
            'enabled' => Pengaturan::where('key', 'whatsapp_enabled')->first()?->value ?? '0',
            'instance_name' => Pengaturan::where('key', 'whatsapp_instance_name')->first()?->value ?? config('services.whatsapp.instance_name'),
            'connected_phone' => Pengaturan::where('key', 'whatsapp_connected_phone')->first()?->value ?? '',
            'notify_tagihan' => Pengaturan::where('key', 'whatsapp_notify_tagihan')->first()?->value ?? '1',
            'notify_pembayaran' => Pengaturan::where('key', 'whatsapp_notify_pembayaran')->first()?->value ?? '1',
            'notify_reminder' => Pengaturan::where('key', 'whatsapp_notify_reminder')->first()?->value ?? '1',
        ];

        // Get connection status
        $connectionStatus = $this->whatsApp->getConnectionStatus();

        return view('admin.whatsapp.index', compact('settings', 'connectionStatus'));
    }

    /**
     * Create WhatsApp instance
     */
    public function createInstance(Request $request)
    {
        $instanceName = Pengaturan::where('key', 'whatsapp_instance_name')->first()?->value 
            ?? config('services.whatsapp.instance_name');

        $result = $this->whatsApp->createInstance($instanceName);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Instance WhatsApp berhasil dibuat',
                'data' => $result['data'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }

    /**
     * Get QR Code for WhatsApp connection
     */
    public function getQrCode()
    {
        $instanceName = Pengaturan::where('key', 'whatsapp_instance_name')->first()?->value 
            ?? config('services.whatsapp.instance_name');

        $result = $this->whatsApp->getQrCode($instanceName);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'qrcode' => $result['qrcode'],
                'pairingCode' => $result['pairingCode'] ?? null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }

    /**
     * Get connection status
     */
    public function getStatus()
    {
        $instanceName = Pengaturan::where('key', 'whatsapp_instance_name')->first()?->value 
            ?? config('services.whatsapp.instance_name');

        $result = $this->whatsApp->getConnectionStatus($instanceName);

        return response()->json($result);
    }

    /**
     * Disconnect WhatsApp
     */
    public function disconnect()
    {
        $instanceName = Pengaturan::where('key', 'whatsapp_instance_name')->first()?->value 
            ?? config('services.whatsapp.instance_name');

        $result = $this->whatsApp->logout($instanceName);

        if ($result['success']) {
            // Update settings
            Pengaturan::updateOrCreate(
                ['key' => 'whatsapp_enabled'],
                ['value' => '0']
            );
            Pengaturan::updateOrCreate(
                ['key' => 'whatsapp_connected_phone'],
                ['value' => '']
            );

            return response()->json([
                'success' => true,
                'message' => 'WhatsApp berhasil diputuskan',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }

    /**
     * Update WhatsApp settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'whatsapp_enabled' => 'required|in:0,1',
            'whatsapp_notify_tagihan' => 'required|in:0,1',
            'whatsapp_notify_pembayaran' => 'required|in:0,1',
            'whatsapp_notify_reminder' => 'required|in:0,1',
        ]);

        $settings = [
            'whatsapp_enabled' => $request->whatsapp_enabled,
            'whatsapp_notify_tagihan' => $request->whatsapp_notify_tagihan,
            'whatsapp_notify_pembayaran' => $request->whatsapp_notify_pembayaran,
            'whatsapp_notify_reminder' => $request->whatsapp_notify_reminder,
        ];

        foreach ($settings as $key => $value) {
            Pengaturan::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.whatsapp.index')
            ->with('success', 'Pengaturan WhatsApp berhasil disimpan');
    }

    /**
     * Send test message
     */
    public function sendTestMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $result = $this->whatsApp->sendMessage($request->phone, $request->message);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan test berhasil dikirim',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }

    /**
     * Restart instance
     */
    public function restartInstance()
    {
        $instanceName = Pengaturan::where('key', 'whatsapp_instance_name')->first()?->value 
            ?? config('services.whatsapp.instance_name');

        // Delete and recreate instance
        $this->whatsApp->deleteInstance($instanceName);
        
        sleep(1); // Wait a bit
        
        $result = $this->whatsApp->createInstance($instanceName);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Instance WhatsApp berhasil di-restart',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }
}
