<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswaAktif = Siswa::active()->count();
        $totalUangKas = Pembayaran::dikonfirmasi()->sum('jumlah_bayar');
        $pesanBaru = Pembayaran::pending()->count();
        $tagihanBaru = Tagihan::baru()->count();

        return view('admin.dashboard', compact('totalSiswaAktif', 'totalUangKas', 'pesanBaru', 'tagihanBaru'));
    }
}
