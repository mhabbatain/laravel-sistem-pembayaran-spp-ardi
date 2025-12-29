<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\WaliMurid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['waliMurid.user', 'createdBy']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $siswa = $query->paginate(10);

        return view('admin.siswa.index', compact('siswa'));
    }

    public function create()
    {
        $waliMurid = WaliMurid::with('user')->get();
        return view('admin.siswa.create', compact('waliMurid'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wali_murid_id' => 'nullable|exists:wali_murid,id',
            'nisn' => 'required|unique:siswa,nisn',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'jurusan' => 'required|string|max:255',
            'angkatan' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'biaya_spp' => 'required|numeric|min:0',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = true;

        Siswa::create($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $siswa = Siswa::with(['waliMurid.user', 'tagihan.detailTagihan.biaya', 'createdBy'])
            ->findOrFail($id);

        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $waliMurid = WaliMurid::with('user')->get();
        return view('admin.siswa.edit', compact('siswa', 'waliMurid'));
    }

    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'wali_murid_id' => 'nullable|exists:wali_murid,id',
            'nisn' => 'required|unique:siswa,nisn,' . $id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'jurusan' => 'required|string|max:255',
            'angkatan' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'biaya_spp' => 'required|numeric|min:0',
        ]);

        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function toggleStatus(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->is_active = !$siswa->is_active;
        $siswa->save();

        $status = $siswa->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Siswa berhasil {$status}.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // TODO: Implement Excel import
        // Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->back()
            ->with('success', 'Data siswa berhasil diimport.');
    }

    public function deleteAll()
    {
        Siswa::truncate();

        return redirect()->back()
            ->with('success', 'Semua data siswa berhasil dihapus.');
    }

    public function activateAll()
    {
        Siswa::query()->update(['is_active' => true]);

        return redirect()->back()
            ->with('success', 'Semua siswa berhasil diaktifkan.');
    }

    public function deactivateAll()
    {
        Siswa::query()->update(['is_active' => false]);

        return redirect()->back()
            ->with('success', 'Semua siswa berhasil dinonaktifkan.');
    }
}
