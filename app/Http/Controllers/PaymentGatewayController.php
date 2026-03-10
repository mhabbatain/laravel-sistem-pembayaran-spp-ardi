<?php

namespace App\Http\Controllers;

use App\Models\TransaksiGateway;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    protected PaymentGatewayService $gatewayService;

    public function __construct(PaymentGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    /**
     * Halaman Checkout Gateway (Tahap 1 & 2)
     * Simulasi halaman payment gateway seperti Midtrans/Xendit
     */
    public function checkout(string $token)
    {
        $transaksi = TransaksiGateway::where('token', $token)
            ->with(['pembayaran.tagihan.siswa', 'metodePembayaran'])
            ->firstOrFail();

        // Check if expired
        if ($transaksi->isExpired() && $transaksi->status === 'pending') {
            $this->gatewayService->expireTransaction($transaksi);
            $transaksi->refresh();
        }

        // If already processed, redirect to status
        if (in_array($transaksi->status, ['authorized', 'settled', 'failed', 'expired'])) {
            return redirect()->route('gateway.status', $token);
        }

        return view('gateway.checkout', compact('transaksi'));
    }

    /**
     * Process checkout - redirect ke halaman bank simulator
     */
    public function process(string $token)
    {
        $transaksi = TransaksiGateway::where('token', $token)->firstOrFail();

        if (!$transaksi->canProcess()) {
            if ($transaksi->isExpired()) {
                $this->gatewayService->expireTransaction($transaksi);
            }
            return redirect()->route('gateway.status', $token);
        }

        // Update status ke processing
        $transaksi->update([
            'status' => 'processing',
            'gateway_response' => array_merge($transaksi->gateway_response ?? [], [
                'stage' => 'processing',
                'processing_at' => now()->toIso8601String(),
                'message' => 'Menghubungkan ke bank/provider...',
            ]),
        ]);

        return redirect()->route('gateway.bank-simulator', $token);
    }

    /**
     * Halaman Bank Simulator (Tahap 3)
     * Simulasi interface bank/e-wallet untuk otorisasi
     */
    public function bankSimulator(string $token)
    {
        $transaksi = TransaksiGateway::where('token', $token)
            ->with(['pembayaran.tagihan.siswa', 'metodePembayaran'])
            ->firstOrFail();

        if (!$transaksi->canProcess()) {
            return redirect()->route('gateway.status', $token);
        }

        return view('gateway.bank-simulator', compact('transaksi'));
    }

    /**
     * Simulasi aksi user di bank (approve/reject)
     * Memproses Tahap 3, 4, 5 secara berurutan
     */
    public function simulate(Request $request, string $token)
    {
        $transaksi = TransaksiGateway::where('token', $token)->firstOrFail();

        if (!$transaksi->canProcess()) {
            return redirect()->route('gateway.status', $token);
        }

        $approved = $request->input('action') === 'approve';

        // Process full flow: Authorization → Confirmation → Settlement
        $this->gatewayService->processFullFlow($transaksi, $approved);

        return redirect()->route('gateway.status', $token);
    }

    /**
     * Halaman Status Transaksi
     * Menampilkan timeline 5 tahap pembayaran
     */
    public function status(string $token)
    {
        $transaksi = TransaksiGateway::where('token', $token)
            ->with(['pembayaran.tagihan.siswa', 'metodePembayaran'])
            ->firstOrFail();

        return view('gateway.status', compact('transaksi'));
    }

    /**
     * API endpoint untuk status check (AJAX polling)
     */
    public function statusCheck(string $token)
    {
        $transaksi = TransaksiGateway::where('token', $token)->firstOrFail();

        return response()->json([
            'status' => $transaksi->status,
            'status_label' => $transaksi->status_label,
            'status_color' => $transaksi->status_color,
            'gateway_response' => $transaksi->gateway_response,
            'authorized_at' => $transaksi->authorized_at?->toIso8601String(),
            'settled_at' => $transaksi->settled_at?->toIso8601String(),
        ]);
    }
}
