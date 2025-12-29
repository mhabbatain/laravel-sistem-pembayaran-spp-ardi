<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
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
        return view('wali.pembayaran.konfirmasi', compact('tagihan', 'rekeningSekolah'));
    }

    public function storeKonfirmasi(Request $request, Tagihan $tagihan)
    {
        // Check if tagihan belongs to current wali's siswa
        if ($tagihan->siswa->wali_murid_id !== auth()->user()->waliMurid->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'rekening_tujuan_id' => 'required|exists:rekening_sekolah,id',
            'tanggal_pembayaran' => 'required|date',
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        // Upload bukti transfer
        $buktiPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'siswa_id' => $tagihan->siswa_id,
            'wali_murid_id' => auth()->user()->waliMurid->id,
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'rekening_tujuan_id' => $validated['rekening_tujuan_id'],
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'bukti_transfer' => $buktiPath,
            'status_konfirmasi' => 'pending',
        ]);

        return redirect()->route('wali.tagihan.show', $tagihan)->with('success', 'Pembayaran berhasil diajukan, menunggu konfirmasi');
    }
}
