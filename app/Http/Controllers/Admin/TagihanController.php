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
    public function index(Request $request)
    {
        $query = Tagihan::with(['siswa', 'detailTagihan.biaya']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($q2) use ($search) {
                    $q2->where('nama', 'like', '%' . $search . '%')
                       ->orWhere('nisn', 'like', '%' . $search . '%');
                })
                ->orWhere('bulan', 'like', '%' . $search . '%')
                ->orWhere('tahun', 'like', '%' . $search . '%');
            });
        }

        $tagihan = $query->latest()->paginate(10)->withQueryString();
        return view('admin.tagihan.index', compact('tagihan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswa = \App\Models\Siswa::with('waliMurid.user')->orderBy('nama')->get();
        $biaya = \App\Models\Biaya::orderBy('nama_biaya')->get();
        return view('admin.tagihan.create', compact('siswa', 'biaya'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'bulan' => 'required|string',
            'tahun' => 'required|string',
            'tanggal_tagihan' => 'required|date',
            'biaya_id' => 'required|array',
            'biaya_id.*' => 'exists:biaya,id',
        ]);

        // Check if tagihan already exists for this siswa, bulan, tahun
        $exists = Tagihan::where('siswa_id', $request->siswa_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tagihan untuk siswa ini pada periode yang sama sudah ada.');
        }

        // Calculate total tagihan from selected biaya
        $totalTagihan = \App\Models\Biaya::whereIn('id', $request->biaya_id)->sum('jumlah');

        // Create tagihan
        $tagihan = Tagihan::create([
            'siswa_id' => $request->siswa_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_tagihan' => $request->tanggal_tagihan,
            'status' => 'baru',
            'total_tagihan' => $totalTagihan,
            'jumlah_bayar' => 0,
            'sisa_tagihan' => $totalTagihan,
            'created_by' => auth()->id(),
        ]);

        // Create detail tagihan
        foreach ($request->biaya_id as $biayaId) {
            $biaya = \App\Models\Biaya::find($biayaId);
            \App\Models\DetailTagihan::create([
                'tagihan_id' => $tagihan->id,
                'biaya_id' => $biayaId,
                'jumlah' => $biaya->jumlah,
            ]);
        }

        return redirect()->route('admin.tagihan.index')
            ->with('success', 'Tagihan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tagihan $tagihan)
    {
        $tagihan->load(['siswa.waliMurid.user', 'detailTagihan.biaya', 'pembayaran.waliMurid.user', 'pembayaran.rekeningTujuan']);
        return view('admin.tagihan.show', compact('tagihan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tagihan $tagihan)
    {
        $tagihan->load('detailTagihan.biaya');
        $siswa = \App\Models\Siswa::with('waliMurid.user')->orderBy('nama')->get();
        $biaya = \App\Models\Biaya::orderBy('nama_biaya')->get();
        $selectedBiaya = $tagihan->detailTagihan->pluck('biaya_id')->toArray();
        return view('admin.tagihan.edit', compact('tagihan', 'siswa', 'biaya', 'selectedBiaya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'bulan' => 'required|string',
            'tahun' => 'required|string',
            'tanggal_tagihan' => 'required|date',
            'biaya_id' => 'required|array',
            'biaya_id.*' => 'exists:biaya,id',
        ]);

        // Check if tagihan already exists for this siswa, bulan, tahun (except current tagihan)
        $exists = Tagihan::where('siswa_id', $request->siswa_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('id', '!=', $tagihan->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tagihan untuk siswa ini pada periode yang sama sudah ada.');
        }

        // Calculate total tagihan from selected biaya
        $totalTagihan = \App\Models\Biaya::whereIn('id', $request->biaya_id)->sum('jumlah');

        // Calculate sisa tagihan based on existing payments
        $sisaTagihan = $totalTagihan - $tagihan->jumlah_bayar;

        // Update tagihan
        $tagihan->update([
            'siswa_id' => $request->siswa_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_tagihan' => $request->tanggal_tagihan,
            'total_tagihan' => $totalTagihan,
            'sisa_tagihan' => $sisaTagihan,
        ]);

        // Delete old detail tagihan
        $tagihan->detailTagihan()->delete();

        // Create new detail tagihan
        foreach ($request->biaya_id as $biayaId) {
            $biaya = \App\Models\Biaya::find($biayaId);
            \App\Models\DetailTagihan::create([
                'tagihan_id' => $tagihan->id,
                'biaya_id' => $biayaId,
                'jumlah' => $biaya->jumlah,
            ]);
        }

        return redirect()->route('admin.tagihan.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tagihan $tagihan)
    {
        // Check if tagihan has pembayaran
        if ($tagihan->pembayaran()->count() > 0) {
            return redirect()->route('admin.tagihan.index')->with('error', 'Tidak dapat menghapus tagihan yang sudah memiliki pembayaran');
        }

        $tagihan->delete();

        return redirect()->route('admin.tagihan.index')->with('success', 'Tagihan berhasil dihapus');
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
