<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of santri.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('waliMurid.user');

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        // Search by name or NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswa = $query->latest()->paginate(10)->withQueryString();
        
        // Get unique kelas for filter
        $kelasList = Siswa::distinct()->pluck('kelas')->sort();

        return view('superadmin.siswa.index', compact('siswa', 'kelasList'));
    }

    /**
     * Display the specified santri.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load(['waliMurid.user', 'tagihan' => function ($query) {
            $query->latest()->take(12);
        }]);
        
        return view('superadmin.siswa.show', compact('siswa'));
    }
}
