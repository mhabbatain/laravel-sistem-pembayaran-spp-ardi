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
        return view('admin.wali-murid.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:20',
        ]);

        // Create user
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'wali',
            'no_hp' => $validated['no_hp'],
            'created_by' => auth()->id(),
        ]);

        // Create wali murid
        WaliMurid::create([
            'user_id' => $user->id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.wali-murid.index')->with('success', 'Wali murid berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(WaliMurid $waliMurid)
    {
        $waliMurid->load(['user', 'siswa']);
        return view('admin.wali-murid.show', compact('waliMurid'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WaliMurid $waliMurid)
    {
        $waliMurid->load('user');
        return view('admin.wali-murid.edit', compact('waliMurid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WaliMurid $waliMurid)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $waliMurid->user_id,
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:20',
        ]);

        // Update user data
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = bcrypt($validated['password']);
        }

        $waliMurid->user->update($userData);

        return redirect()->route('admin.wali-murid.index')->with('success', 'Wali murid berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaliMurid $waliMurid)
    {
        // Check if wali has siswa
        if ($waliMurid->siswa()->count() > 0) {
            return redirect()->route('admin.wali-murid.index')->with('error', 'Tidak dapat menghapus wali murid yang masih memiliki siswa');
        }

        $user = $waliMurid->user;
        $waliMurid->delete();
        $user->delete();

        return redirect()->route('admin.wali-murid.index')->with('success', 'Wali murid berhasil dihapus');
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
