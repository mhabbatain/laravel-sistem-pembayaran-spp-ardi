<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaliMurid;
use Illuminate\Http\Request;

class WaliMuridController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waliMurid = WaliMurid::with(['user', 'siswa'])->latest()->paginate(10);
        return view('admin.wali-murid.index', compact('waliMurid'));
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
    public function show(WaliMurid $waliMurid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WaliMurid $waliMurid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WaliMurid $waliMurid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaliMurid $waliMurid)
    {
        //
    }

    /**
     * Display detail wali murid with all siswa
     */
    public function detail(WaliMurid $waliMurid)
    {
        $waliMurid->load(['user', 'siswa']);
        return view('admin.wali-murid.detail', compact('waliMurid'));
    }

    /**
     * Add siswa to wali murid
     */
    public function addSiswa(Request $request, WaliMurid $waliMurid)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
        ]);

        // Update siswa's wali_murid_id
        \App\Models\Siswa::findOrFail($validated['siswa_id'])->update([
            'wali_murid_id' => $waliMurid->id
        ]);

        return redirect()->route('admin.wali-murid.detail', $waliMurid)->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Remove siswa from wali murid
     */
    public function removeSiswa($waliId, $siswaId)
    {
        $siswa = \App\Models\Siswa::findOrFail($siswaId);
        $siswa->update(['wali_murid_id' => null]);

        return redirect()->route('admin.wali-murid.detail', $waliId)->with('success', 'Siswa berhasil dihapus dari wali murid');
    }
}
