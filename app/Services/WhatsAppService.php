<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $instanceName;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
        $this->instanceName = config('services.whatsapp.instance_name');
    }

    /**
     * Get HTTP client with headers
     */
    protected function http()
    {
        return Http::withHeaders([
            'apikey' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30);
    }

    /**
     * Create WhatsApp instance
     */
    public function createInstance(?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;

        try {
            $response = $this->http()->post("{$this->apiUrl}/instance/create", [
                'instanceName' => $name,
                'qrcode' => true,
                'integration' => 'WHATSAPP-BAILEYS',
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp instance created', ['instance' => $name]);
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            // Check if instance already exists (403 error)
            $responseData = $response->json();
            $errorMessage = $responseData['response']['message'][0] ?? '';
            
            if ($response->status() === 403 && str_contains($errorMessage, 'already in use')) {
                Log::info('WhatsApp instance already exists, reusing', ['instance' => $name]);
                return [
                    'success' => true,
                    'data' => ['instanceName' => $name, 'existing' => true],
                    'message' => 'Instance sudah ada, silakan scan QR Code',
                ];
            }

            Log::error('Failed to create WhatsApp instance', [
                'response' => $responseData,
            ]);

            return [
                'success' => false,
                'message' => $responseData['message'] ?? $errorMessage ?? 'Gagal membuat instance WhatsApp',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp createInstance error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server WhatsApp: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get QR Code for connecting WhatsApp
     */
    public function getQrCode(?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;

        try {
            // Try multiple times as QR code generation may take a moment
            $maxRetries = 5;
            $retryDelay = 2; // seconds
            
            for ($i = 0; $i < $maxRetries; $i++) {
                $response = $this->http()->get("{$this->apiUrl}/instance/connect/{$name}");

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Check for QR code in different response formats
                    $qrcode = $data['base64'] ?? $data['qrcode']['base64'] ?? null;
                    
                    // If count is 0 and no QR code, the instance might still be initializing
                    if (isset($data['count']) && $data['count'] === 0 && !$qrcode) {
                        Log::info('QR code not ready yet, retrying...', ['attempt' => $i + 1]);
                        sleep($retryDelay);
                        continue;
                    }
                    
                    if ($qrcode) {
                        return [
                            'success' => true,
                            'qrcode' => $qrcode,
                            'pairingCode' => $data['pairingCode'] ?? null,
                            'data' => $data,
                        ];
                    }
                }
            }
            
            // If after retries still no QR code, return error
            Log::warning('QR code not available after retries', ['instance' => $name]);
            return [
                'success' => false,
                'message' => 'QR Code belum tersedia. Silakan coba lagi dalam beberapa detik.',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp getQrCode error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server WhatsApp: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check connection status
     */
    public function getConnectionStatus(?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;

        try {
            $response = $this->http()->get("{$this->apiUrl}/instance/connectionState/{$name}");

            if ($response->successful()) {
                $data = $response->json();
                $state = $data['state'] ?? $data['instance']['state'] ?? 'unknown';
                $connected = $state === 'open';
                
                // Get phone number from fetchInstances if connected
                $phoneNumber = null;
                $profileName = null;
                if ($connected) {
                    $instanceInfo = $this->getInstanceInfo($name);
                    $phoneNumber = $instanceInfo['phoneNumber'] ?? null;
                    $profileName = $instanceInfo['profileName'] ?? null;
                }
                
                return [
                    'success' => true,
                    'connected' => $connected,
                    'state' => $state,
                    'phoneNumber' => $phoneNumber,
                    'profileName' => $profileName,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'connected' => false,
                'message' => $response->json()['message'] ?? 'Gagal mendapatkan status',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp getConnectionStatus error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'connected' => false,
                'message' => 'Tidak dapat terhubung ke server WhatsApp: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get instance info including phone number
     */
    public function getInstanceInfo(?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;

        try {
            $response = $this->http()->get("{$this->apiUrl}/instance/fetchInstances", [
                'instanceName' => $name,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // For v1.x, response is directly the instance object
                // For v2.x, it might be an array
                $instance = is_array($data) && isset($data[0]) ? $data[0] : ($data['instance'] ?? $data);
                
                // Extract owner JID (phone number) from ownerJid field
                $ownerJid = $instance['ownerJid'] ?? $instance['owner'] ?? null;
                $phoneNumber = null;
                
                if ($ownerJid) {
                    // ownerJid format: 628xxx@s.whatsapp.net
                    $phoneNumber = str_replace('@s.whatsapp.net', '', $ownerJid);
                    // Format to readable: +62 8xxx
                    if (strlen($phoneNumber) > 10) {
                        $phoneNumber = '+' . substr($phoneNumber, 0, 2) . ' ' . substr($phoneNumber, 2);
                    }
                }
                
                return [
                    'success' => true,
                    'phoneNumber' => $phoneNumber,
                    'profileName' => $instance['profileName'] ?? null,
                    'profilePicUrl' => $instance['profilePicUrl'] ?? null,
                    'status' => $instance['connectionStatus'] ?? $instance['status'] ?? 'unknown',
                ];
            }

            return ['success' => false];
        } catch (Exception $e) {
            Log::error('WhatsApp getInstanceInfo error', ['error' => $e->getMessage()]);
            return ['success' => false];
        }
    }

    /**
     * Logout / Disconnect WhatsApp
     */
    public function logout(?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;

        try {
            $response = $this->http()->delete("{$this->apiUrl}/instance/logout/{$name}");

            if ($response->successful()) {
                Log::info('WhatsApp logged out', ['instance' => $name]);
                return [
                    'success' => true,
                    'message' => 'WhatsApp berhasil diputuskan',
                ];
            }

            // Handle specific error cases
            $responseData = $response->json();
            $errorMessage = $responseData['response']['message'][0] ?? $responseData['message'] ?? 'Gagal logout';
            
            // If instance is not connected, treat as success (already disconnected)
            if (str_contains($errorMessage, 'not connected')) {
                return [
                    'success' => true,
                    'message' => 'WhatsApp sudah dalam keadaan tidak terhubung',
                ];
            }

            return [
                'success' => false,
                'message' => $errorMessage,
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp logout error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server WhatsApp: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete instance
     */
    public function deleteInstance(?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;

        try {
            $response = $this->http()->delete("{$this->apiUrl}/instance/delete/{$name}");

            if ($response->successful()) {
                Log::info('WhatsApp instance deleted', ['instance' => $name]);
                return [
                    'success' => true,
                    'message' => 'Instance WhatsApp berhasil dihapus',
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Gagal menghapus instance',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp deleteInstance error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server WhatsApp: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Format phone number to WhatsApp format (with country code)
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 62 (Indonesia)
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // If doesn't start with country code, add 62
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Send text message
     */
    public function sendMessage(string $phone, string $message, ?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;
        $formattedPhone = $this->formatPhoneNumber($phone);

        try {
            $response = $this->http()->post("{$this->apiUrl}/message/sendText/{$name}", [
                'number' => $formattedPhone,
                'text' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent', [
                    'phone' => $formattedPhone,
                    'instance' => $name,
                ]);
                return [
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim',
                    'data' => $response->json(),
                ];
            }

            Log::error('Failed to send WhatsApp message', [
                'phone' => $formattedPhone,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Gagal mengirim pesan',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp sendMessage error', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Tidak dapat mengirim pesan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send message with image
     */
    public function sendImage(string $phone, string $imageUrl, ?string $caption = null, ?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;
        $formattedPhone = $this->formatPhoneNumber($phone);

        try {
            $response = $this->http()->post("{$this->apiUrl}/message/sendMedia/{$name}", [
                'number' => $formattedPhone,
                'mediatype' => 'image',
                'media' => $imageUrl,
                'caption' => $caption,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp image sent', [
                    'phone' => $formattedPhone,
                    'instance' => $name,
                ]);
                return [
                    'success' => true,
                    'message' => 'Gambar berhasil dikirim',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Gagal mengirim gambar',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp sendImage error', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Tidak dapat mengirim gambar: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send document/file
     */
    public function sendDocument(string $phone, string $documentUrl, string $filename, ?string $caption = null, ?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;
        $formattedPhone = $this->formatPhoneNumber($phone);

        try {
            $response = $this->http()->post("{$this->apiUrl}/message/sendMedia/{$name}", [
                'number' => $formattedPhone,
                'mediatype' => 'document',
                'media' => $documentUrl,
                'fileName' => $filename,
                'caption' => $caption,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp document sent', [
                    'phone' => $formattedPhone,
                    'instance' => $name,
                ]);
                return [
                    'success' => true,
                    'message' => 'Dokumen berhasil dikirim',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Gagal mengirim dokumen',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp sendDocument error', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Tidak dapat mengirim dokumen: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check if number is registered on WhatsApp
     */
    public function isOnWhatsApp(string $phone, ?string $instanceName = null): array
    {
        $name = $instanceName ?? $this->instanceName;
        $formattedPhone = $this->formatPhoneNumber($phone);

        try {
            $response = $this->http()->post("{$this->apiUrl}/chat/whatsappNumbers/{$name}", [
                'numbers' => [$formattedPhone],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $exists = false;

                if (isset($data[0]['exists'])) {
                    $exists = $data[0]['exists'];
                }

                return [
                    'success' => true,
                    'exists' => $exists,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'exists' => false,
                'message' => $response->json()['message'] ?? 'Gagal memeriksa nomor',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp isOnWhatsApp error', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'exists' => false,
                'message' => 'Tidak dapat memeriksa nomor: ' . $e->getMessage(),
            ];
        }
    }
}
