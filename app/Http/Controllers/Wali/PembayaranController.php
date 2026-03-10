<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function konfirmasi(Tagihan $tagihan)
    {
        // Check if tagihan belongs to current wali's siswa
        if ($tagihan->siswa->wali_murid_id !== auth()->user()->waliMurid->id) {
            abort(403, 'Unauthorized');
        }

        $tagihan->load(['siswa', 'detailTagihan.biaya']);
        $rekeningSekolah = \App\Models\RekeningSekolah::where('is_active', true)->get();
        
        // Get metode pembayaran grouped by kategori
        $metodePembayaran = MetodePembayaran::active()->ordered()->get()->groupBy('kategori');
        
        return view('wali.pembayaran.konfirmasi', compact('tagihan', 'rekeningSekolah', 'metodePembayaran'));
    }

    public function storeKonfirmasi(Request $request, Tagihan $tagihan)
    {
        // Check if tagihan belongs to current wali's siswa
        if ($tagihan->siswa->wali_murid_id !== auth()->user()->waliMurid->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'detail_ids' => 'nullable|array',
            'detail_ids.*' => 'exists:detail_tagihan,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'rekening_tujuan_id' => 'required_unless:is_ewallet,1|nullable|exists:rekening_sekolah,id',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id',
            'bukti_transfer' => 'required_unless:is_ewallet,1|image|max:2048',
        ]);

        $metode = \App\Models\MetodePembayaran::find($validated['metode_pembayaran_id']);

        // Check if at least one detail is selected
        if (empty($validated['detail_ids'])) {
            return back()->withErrors(['detail_ids' => 'Pilih minimal satu komponen pembayaran'])->withInput();
        }

        // Verify detail_ids belong to this tagihan and load with biaya relation
        $detailTagihan = \App\Models\DetailTagihan::with('biaya')
            ->whereIn('id', $validated['detail_ids'])
            ->where('tagihan_id', $tagihan->id)
            ->get();
        
        if ($detailTagihan->count() !== count($validated['detail_ids'])) {
            return back()->withErrors(['detail_ids' => 'Detail tagihan tidak valid'])->withInput();
        }

        // Calculate total from selected details (remaining amount)
        $totalPilihan = $detailTagihan->sum(function($detail) {
            return $detail->jumlah - $detail->jumlah_dibayar;
        });

        // Allow small floating point difference
        if (abs($totalPilihan - $validated['jumlah_bayar']) > 1) {
            return back()->withErrors(['jumlah_bayar' => 'Jumlah pembayaran tidak sesuai dengan komponen yang dipilih. Total: Rp ' . number_format($totalPilihan, 0, ',', '.')])->withInput();
        }

        // Determine if this is a gateway payment (e-wallet, QRIS, kartu)
        $isGatewayPayment = in_array($metode->kategori, ['e_wallet', 'qris', 'kartu']);

        if ($isGatewayPayment) {
            // ===========================================
            // FLOW PAYMENT GATEWAY (Simulasi)
            // ===========================================
            
            // Create pembayaran record dengan status pending
            $pembayaran = Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'wali_murid_id' => auth()->user()->waliMurid->id,
                'metode_pembayaran' => 'transfer',
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'rekening_tujuan_id' => null,
                'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
                'bukti_pembayaran' => null,
                'status_konfirmasi' => 'pending',
                'catatan' => 'Via Payment Gateway (' . $metode->nama . '). Komponen: ' . $detailTagihan->pluck('biaya.nama_biaya')->implode(', ')
            ]);

            // Create transaksi gateway via service
            $gatewayService = app(PaymentGatewayService::class);
            $transaksi = $gatewayService->createTransaction($pembayaran, $metode);

            // Redirect ke halaman gateway checkout
            return redirect()->route('gateway.checkout', $transaksi->token);

        } else {
            // ===========================================
            // FLOW MANUAL BANK TRANSFER (tetap sama)
            // ===========================================

            // Handle Proof of Payment (Bukti Transfer)
            $buktiPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');

            // Create pembayaran record
            $pembayaran = Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'wali_murid_id' => auth()->user()->waliMurid->id,
                'metode_pembayaran' => 'transfer',
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'rekening_tujuan_id' => $validated['rekening_tujuan_id'],
                'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
                'bukti_pembayaran' => $buktiPath,
                'status_konfirmasi' => 'pending',
                'catatan' => 'Via ' . $metode->nama . '. Komponen: ' . $detailTagihan->pluck('biaya.nama_biaya')->implode(', ')
            ]);

            return redirect()->route('wali.tagihan.show', $tagihan)->with('success', 'Pembayaran berhasil diajukan, menunggu konfirmasi admin.');
        }
    }
}
