<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Laporan rekap pembayaran SPP
     */
    public function rekapPembayaran(Request $request)
    {
        $bulan = (int) ($request->bulan ?? date('m'));
        $tahun = (int) ($request->tahun ?? date('Y'));

        // Get pembayaran yang dikonfirmasi per bulan
        $pembayaranBulanan = Pembayaran::dikonfirmasi()
            ->whereYear('tanggal_konfirmasi', $tahun)
            ->selectRaw('MONTH(tanggal_konfirmasi) as bulan, SUM(jumlah_bayar) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Total pembayaran per kelas
        $pembayaranPerKelas = Pembayaran::dikonfirmasi()
            ->whereYear('tanggal_konfirmasi', $tahun)
            ->whereMonth('tanggal_konfirmasi', $bulan)
            ->join('tagihan', 'pembayaran.tagihan_id', '=', 'tagihan.id')
            ->join('siswa', 'tagihan.siswa_id', '=', 'siswa.id')
            ->selectRaw('siswa.kelas, COUNT(DISTINCT pembayaran.id) as jumlah_transaksi, SUM(pembayaran.jumlah_bayar) as total')
            ->groupBy('siswa.kelas')
            ->orderBy('siswa.kelas')
            ->get();

        // Total keseluruhan
        $totalPembayaran = Pembayaran::dikonfirmasi()->sum('jumlah_bayar');
        $totalBulanIni = Pembayaran::dikonfirmasi()
            ->whereYear('tanggal_konfirmasi', $tahun)
            ->whereMonth('tanggal_konfirmasi', $bulan)
            ->sum('jumlah_bayar');

        // List tahun untuk filter
        $tahunList = Pembayaran::selectRaw('YEAR(tanggal_konfirmasi) as tahun')
            ->distinct()
            ->whereNotNull('tanggal_konfirmasi')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($tahunList->isEmpty()) {
            $tahunList = collect([date('Y')]);
        }

        return view('superadmin.laporan.rekap-pembayaran', compact(
            'pembayaranBulanan',
            'pembayaranPerKelas',
            'totalPembayaran',
            'totalBulanIni',
            'bulan',
            'tahun',
            'tahunList'
        ));
    }

    /**
     * Laporan pembayaran detail
     */
    public function pembayaran(Request $request)
    {
        $query = Pembayaran::with(['tagihan.siswa', 'rekeningSekolah', 'dikonfirmasiOleh']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_konfirmasi', $request->status);
        }

        // Filter by date range
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }

        // Filter by bulan tagihan
        if ($request->filled('bulan_tagihan')) {
            $query->whereHas('tagihan', function ($q) use ($request) {
                $q->where('bulan', $request->bulan_tagihan);
            });
        }

        // Filter by tahun tagihan
        if ($request->filled('tahun_tagihan')) {
            $query->whereHas('tagihan', function ($q) use ($request) {
                $q->where('tahun', $request->tahun_tagihan);
            });
        }

        $pembayaran = $query->latest()->paginate(15)->withQueryString();

        // Summary
        $totalDikonfirmasi = Pembayaran::dikonfirmasi()
            ->when($request->filled('dari_tanggal'), fn($q) => $q->whereDate('tanggal_konfirmasi', '>=', $request->dari_tanggal))
            ->when($request->filled('sampai_tanggal'), fn($q) => $q->whereDate('tanggal_konfirmasi', '<=', $request->sampai_tanggal))
            ->sum('jumlah_bayar');

        // List tahun untuk filter
        $tahunList = Tagihan::distinct()->pluck('tahun')->sort()->reverse();

        return view('superadmin.laporan.pembayaran', compact(
            'pembayaran',
            'totalDikonfirmasi',
            'tahunList'
        ));
    }

    /**
     * Laporan tagihan
     */
    public function tagihan(Request $request)
    {
        $query = Tagihan::with('siswa');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        $tagihan = $query->latest()->paginate(15)->withQueryString();

        // Summary
        $summary = [
            'total_tagihan' => Tagihan::sum('total_tagihan'),
            'total_terbayar' => Tagihan::sum('jumlah_bayar'),
            'total_sisa' => Tagihan::sum('sisa_tagihan'),
            'jumlah_lunas' => Tagihan::where('status', 'lunas')->count(),
            'jumlah_belum_lunas' => Tagihan::whereIn('status', ['baru', 'cicilan'])->count(),
        ];

        // List tahun dan kelas untuk filter
        $tahunList = Tagihan::distinct()->pluck('tahun')->sort()->reverse();
        $kelasList = Siswa::distinct()->pluck('kelas')->sort();

        return view('superadmin.laporan.tagihan', compact(
            'tagihan',
            'summary',
            'tahunList',
            'kelasList'
        ));
    }
}
