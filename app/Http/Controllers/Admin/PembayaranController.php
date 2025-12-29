<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
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
        } elseif ($tagihan->jumlah_bayar > 0) {
            $tagihan->status = 'cicilan';
        }

        $tagihan->save();

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
