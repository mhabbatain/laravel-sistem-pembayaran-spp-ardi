<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\WaliMurid;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswaAktif = Siswa::active()->count();
        $totalWaliMurid = WaliMurid::count();
        $totalOperator = User::where('role', 'admin')->count();
        $totalUangMasuk = Pembayaran::dikonfirmasi()->sum('jumlah_bayar');
        $totalTagihan = Tagihan::sum('total_tagihan');
        $totalLunas = Tagihan::where('status', 'lunas')->count();
        $totalBelumLunas = Tagihan::whereIn('status', ['baru', 'cicilan'])->count();

        return view('superadmin.dashboard', compact(
            'totalSiswaAktif',
            'totalWaliMurid',
            'totalOperator',
            'totalUangMasuk',
            'totalTagihan',
            'totalLunas',
            'totalBelumLunas'
        ));
    }
}
