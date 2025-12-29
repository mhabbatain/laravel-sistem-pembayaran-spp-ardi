<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\WaliMurid;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::with('waliMurid')->latest()->paginate(10);
        return view('admin.siswa.index', compact('siswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $waliMurid = WaliMurid::with('user')->get();
        return view('admin.siswa.create', compact('waliMurid'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wali_murid_id' => 'nullable|exists:wali_murid,id',
            'nisn' => 'required|string|unique:siswa,nisn',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|string|max:10',
            'kelas' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        Siswa::create($validated);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load('waliMurid', 'tagihan');
        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $waliMurid = WaliMurid::with('user')->get();
        return view('admin.siswa.edit', compact('siswa', 'waliMurid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'wali_murid_id' => 'nullable|exists:wali_murid,id',
            'nisn' => 'required|string|unique:siswa,nisn,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|string|max:10',
            'kelas' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus');
    }

    /**
     * Toggle status siswa (is_active)
     */
    public function toggleStatus(Siswa $siswa)
    {
        $siswa->update([
            'is_active' => !$siswa->is_active
        ]);

        $status = $siswa->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.siswa.index')->with('success', "Siswa berhasil {$status}");
    }
}
