<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biaya;
use Illuminate\Http\Request;

class BiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $biaya = Biaya::latest()->paginate(10);
        return view('admin.biaya.index', compact('biaya'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.biaya.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_biaya' => 'required|string|max:255',
            'kode' => 'required|string|unique:biaya,kode',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Biaya::create($validated);

        return redirect()->route('admin.biaya.index')->with('success', 'Biaya berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Biaya $biaya)
    {
        return view('admin.biaya.show', compact('biaya'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Biaya $biaya)
    {
        return view('admin.biaya.edit', compact('biaya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Biaya $biaya)
    {
        $validated = $request->validate([
            'nama_biaya' => 'required|string|max:255',
            'kode' => 'required|string|unique:biaya,kode,' . $biaya->id,
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $biaya->update($validated);

        return redirect()->route('admin.biaya.index')->with('success', 'Biaya berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Biaya $biaya)
    {
        $biaya->delete();

        return redirect()->route('admin.biaya.index')->with('success', 'Biaya berhasil dihapus');
    }
}
