<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    protected WhatsAppNotificationService $waNotification;

    public function __construct(WhatsAppNotificationService $waNotification)
    {
        $this->waNotification = $waNotification;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaran = Pembayaran::with(['tagihan.siswa', 'rekeningSekolah'])->latest()->paginate(10);
        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['tagihan.siswa', 'waliMurid.user', 'rekeningTujuan', 'dikonfirmasiOleh']);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Konfirmasi pembayaran
     */
    public function konfirmasi(Pembayaran $pembayaran)
    {
        $pembayaran->update([
            'status_konfirmasi' => 'dikonfirmasi',
            'tanggal_konfirmasi' => now(),
            'dikonfirmasi_oleh' => auth()->id(),
        ]);

        // Update status tagihan
        $tagihan = $pembayaran->tagihan;
        $tagihan->jumlah_bayar += $pembayaran->jumlah_bayar;
        $tagihan->sisa_tagihan = $tagihan->total_tagihan - $tagihan->jumlah_bayar;

        if ($tagihan->sisa_tagihan <= 0) {
            $tagihan->status = 'lunas';
        }

        $tagihan->save();

        // Send WhatsApp notification
        $notifyPembayaran = Pengaturan::where('key', 'whatsapp_notify_pembayaran')->first()?->value ?? '1';
        if ($notifyPembayaran === '1') {
            try {
                $this->waNotification->sendPembayaranKonfirmasiNotification($pembayaran);
            } catch (\Exception $e) {
                Log::error('Failed to send WhatsApp notification', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->route('admin.pembayaran.show', $pembayaran)->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    /**
     * Tolak pembayaran
     */
    public function tolak(Pembayaran $pembayaran)
    {
        $pembayaran->update([
            'status_konfirmasi' => 'ditolak',
            'tanggal_konfirmasi' => now(),
            'dikonfirmasi_oleh' => auth()->id(),
        ]);

        return redirect()->route('admin.pembayaran.show', $pembayaran)->with('success', 'Pembayaran ditolak');
    }
}
