<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $waliMurid = auth()->user()->waliMurid;
        $siswa = $waliMurid->siswa()
            ->where('is_active', true)
            ->with(['createdBy', 'tagihan.detailTagihan.biaya'])
            ->get();
        return view('wali.siswa.index', compact('siswa'));
    }

    public function kartuSpp(Siswa $siswa)
    {
        // Check if siswa belongs to current wali
        if ($siswa->wali_murid_id !== auth()->user()->waliMurid->id) {
            abort(403, 'Unauthorized');
        }

        $siswa->load(['tagihan.detailTagihan.biaya']);
        return view('wali.siswa.kartu-spp', compact('siswa'));
    }
}
