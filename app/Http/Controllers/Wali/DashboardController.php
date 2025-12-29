<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\WaliMurid;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $waliMurid = WaliMurid::where('user_id', $user->id)->first();

        if (!$waliMurid) {
            return view('wali.dashboard', [
                'jumlahAnak' => 0,
                'tagihanBelumLunas' => 0,
                'totalTunggakan' => 0,
                'tagihanTerbaru' => collect([])
            ]);
        }

        $siswaIds = $waliMurid->siswa->pluck('id');

        $jumlahAnak = $waliMurid->siswa->count();
        $tagihanBelumLunas = Tagihan::whereIn('siswa_id', $siswaIds)
            ->where('status', '!=', 'lunas')
            ->count();
        $totalTunggakan = Tagihan::whereIn('siswa_id', $siswaIds)
            ->where('status', '!=', 'lunas')
            ->sum('sisa_tagihan');

        $tagihanTerbaru = Tagihan::with('siswa')
            ->whereIn('siswa_id', $siswaIds)
            ->latest()
            ->take(5)
            ->get();

        return view('wali.dashboard', compact(
            'jumlahAnak',
            'tagihanBelumLunas',
            'totalTunggakan',
            'tagihanTerbaru'
        ));
    }
}
