<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekeningSekolah;
use Illuminate\Http\Request;

class RekeningSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekening = RekeningSekolah::latest()->paginate(10);
        return view('admin.rekening-sekolah.index', compact('rekening'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rekening-sekolah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_transfer' => 'required|string|unique:rekening_sekolah,kode_transfer',
            'nama_bank' => 'required|string|max:255',
            'pemilik_rekening' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        RekeningSekolah::create($validated);

        return redirect()->route('admin.rekening-sekolah.index')->with('success', 'Rekening sekolah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(RekeningSekolah $rekeningSekolah)
    {
        return view('admin.rekening-sekolah.show', compact('rekeningSekolah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekeningSekolah $rekeningSekolah)
    {
        return view('admin.rekening-sekolah.edit', compact('rekeningSekolah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekeningSekolah $rekeningSekolah)
    {
        $validated = $request->validate([
            'kode_transfer' => 'required|string|unique:rekening_sekolah,kode_transfer,' . $rekeningSekolah->id,
            'nama_bank' => 'required|string|max:255',
            'pemilik_rekening' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $rekeningSekolah->update($validated);

        return redirect()->route('admin.rekening-sekolah.index')->with('success', 'Rekening sekolah berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekeningSekolah $rekeningSekolah)
    {
        $rekeningSekolah->delete();

        return redirect()->route('admin.rekening-sekolah.index')->with('success', 'Rekening sekolah berhasil dihapus');
    }
}
