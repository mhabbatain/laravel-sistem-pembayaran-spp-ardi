<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $waliMurid = auth()->user()->waliMurid;
        $tagihan = Tagihan::with(['siswa', 'detailTagihan.biaya'])
            ->whereHas('siswa', function ($query) use ($waliMurid) {
                $query->where('wali_murid_id', $waliMurid->id);
            })
            ->latest()
            ->paginate(10);
        return view('wali.tagihan.index', compact('tagihan'));
    }

    public function show(Tagihan $tagihan)
    {
        // Check if tagihan belongs to current wali's siswa
        if ($tagihan->siswa->wali_murid_id !== auth()->user()->waliMurid->id) {
            abort(403, 'Unauthorized');
        }

        $tagihan->load(['siswa', 'detailTagihan.biaya', 'pembayaran']);
        return view('wali.tagihan.show', compact('tagihan'));
    }
}
