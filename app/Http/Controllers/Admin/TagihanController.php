<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tagihan = Tagihan::with(['siswa', 'detailTagihan.biaya'])->latest()->paginate(10);
        return view('admin.tagihan.index', compact('tagihan'));
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
    public function show(Tagihan $tagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tagihan $tagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tagihan $tagihan)
    {
        //
    }

    /**
     * Display detail tagihan
     */
    public function detail(Tagihan $tagihan)
    {
        $tagihan->load(['siswa', 'detailTagihan.biaya', 'pembayaran']);
        return view('admin.tagihan.detail', compact('tagihan'));
    }

    /**
     * Bayar tagihan (untuk testing)
     */
    public function bayar(Request $request, Tagihan $tagihan)
    {
        // This method would typically be used by wali, not admin
        // For now, just a placeholder
        return redirect()->route('admin.tagihan.detail', $tagihan)->with('info', 'Method bayar belum diimplementasi');
    }

    /**
     * Cetak kartu tagihan
     */
    public function cetakKartu(Tagihan $tagihan)
    {
        $tagihan->load(['siswa', 'detailTagihan.biaya']);
        return view('admin.tagihan.cetak-kartu', compact('tagihan'));
    }
}
