<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
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

        // Handle Proof of Payment (Bukti Transfer)
        if ($metode->kategori === 'e_wallet') {
            // Use default proof for e-wallet
            $defaultFile = 'pembayaran/bukti-dana.png';
            $extension = pathinfo($defaultFile, PATHINFO_EXTENSION);
            $newFileName = 'bukti-transfer/' . uniqid() . '_auto.' . $extension;
            
            // Check if file exists in public
            if (file_exists(public_path($defaultFile))) {
                // Copy to storage/app/public/bukti-transfer/
                if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('bukti-transfer')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('bukti-transfer');
                }
                
                copy(public_path($defaultFile), storage_path('app/public/' . $newFileName));
                $buktiPath = $newFileName;
            } else {
                // Fallback to upload if default file is missing
                if (!$request->hasFile('bukti_transfer')) {
                     return back()->withErrors(['bukti_transfer' => 'File bukti-dana.png tidak ditemukan di sistem, silakan upload bukti secara manual.'])->withInput();
                }
                $buktiPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
            }
        } else {
            // Traditional upload for other methods
            $buktiPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
        }

        // Create pembayaran record
        $pembayaran = Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'wali_murid_id' => auth()->user()->waliMurid->id,
            'metode_pembayaran' => 'transfer', // Matching enum in DB
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'rekening_tujuan_id' => $validated['rekening_tujuan_id'],
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'bukti_pembayaran' => $buktiPath,
            'status_konfirmasi' => $metode->kategori === 'e_wallet' ? 'dikonfirmasi' : 'pending',
            'tanggal_konfirmasi' => $metode->kategori === 'e_wallet' ? now() : null,
            'dikonfirmasi_oleh' => $metode->kategori === 'e_wallet' ? 1 : null, // Auto-confirmed by system (Admin ID 1)
            'catatan' => 'Via ' . $metode->nama . ($metode->kategori === 'e_wallet' ? ' [Otomatis Lunas]' : '') . '. Komponen: ' . $detailTagihan->pluck('biaya.nama_biaya')->implode(', ')
        ]);

        // Auto-update tagihan if e-wallet
        if ($metode->kategori === 'e_wallet') {
            $tagihan->jumlah_bayar += $pembayaran->jumlah_bayar;
            $tagihan->sisa_tagihan = $tagihan->total_tagihan - $tagihan->jumlah_bayar;
            if ($tagihan->sisa_tagihan <= 0) {
                $tagihan->status = 'lunas';
            }
            $tagihan->save();

            // Send WhatsApp notification
            $waService = app(\App\Services\WhatsAppNotificationService::class);
            $notifyPembayaran = \App\Models\Pengaturan::where('key', 'whatsapp_notify_pembayaran')->first()?->value ?? '1';
            if ($notifyPembayaran === '1') {
                try {
                    $waService->sendPembayaranKonfirmasiNotification($pembayaran);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send WhatsApp notification for auto-payment', ['error' => $e->getMessage()]);
                }
            }
        }

        $message = $metode->kategori === 'e_wallet' 
            ? 'Pembayaran berhasil dikonfirmasi secara otomatis.' 
            : 'Pembayaran berhasil diajukan, menunggu konfirmasi.';

        return redirect()->route('wali.tagihan.show', $tagihan)->with('success', $message);
    }
}
