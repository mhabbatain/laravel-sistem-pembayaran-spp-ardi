<?php

namespace App\Jobs;

use App\Models\Tagihan;
use App\Models\Pengaturan;
use App\Services\WhatsAppNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendWhatsAppTagihanNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 60;

    protected Tagihan $tagihan;

    /**
     * Create a new job instance.
     */
    public function __construct(Tagihan $tagihan)
    {
        $this->tagihan = $tagihan;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppNotificationService $waNotification): void
    {
        // Check if notification is enabled
        $notifyTagihan = Pengaturan::where('key', 'whatsapp_notify_tagihan')->first()?->value ?? '1';
        
        if ($notifyTagihan !== '1') {
            Log::info('WhatsApp tagihan notification skipped - disabled', [
                'tagihan_id' => $this->tagihan->id,
            ]);
            return;
        }

        try {
            $result = $waNotification->sendTagihanNotification($this->tagihan);
            
            if (!$result['success']) {
                Log::warning('WhatsApp tagihan notification failed', [
                    'tagihan_id' => $this->tagihan->id,
                    'message' => $result['message'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp tagihan notification error', [
                'tagihan_id' => $this->tagihan->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e; // Will retry based on $tries
        }
    }
}
