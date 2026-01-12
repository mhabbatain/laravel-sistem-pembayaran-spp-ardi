<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * Get pengaturan as object
     */
    private function getPengaturan()
    {
        return (object) [
            'nama_sekolah' => Pengaturan::get('nama_instansi', 'Nama Sekolah'),
            'alamat' => Pengaturan::get('alamat_instansi', 'Alamat Sekolah'),
            'no_telp' => Pengaturan::get('telepon_instansi', '-'),
            'email' => Pengaturan::get('email_instansi', '-'),
            'logo' => Pengaturan::get('logo'),
            'kepala_sekolah' => Pengaturan::get('kepala_sekolah', 'Kepala Sekolah'),
            'nip_kepala_sekolah' => Pengaturan::get('nip_kepala_sekolah', '-'),
            'bendahara' => Pengaturan::get('bendahara', 'Bendahara'),
            'nip_bendahara' => Pengaturan::get('nip_bendahara', '-'),
        ];
    }

    /**
     * Generate Invoice Tagihan PDF
     */
    public function invoiceTagihan(Tagihan $tagihan)
    {
        $tagihan->load(['siswa.waliMurid.user', 'detailTagihan.biaya']);
        
        $pengaturan = $this->getPengaturan();
        
        $pdf = Pdf::loadView('pdf.invoice-tagihan', [
            'tagihan' => $tagihan,
            'pengaturan' => $pengaturan,
        ]);
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('invoice-tagihan-' . $tagihan->siswa->nisn . '-' . $tagihan->bulan . '-' . $tagihan->tahun . '.pdf');
    }

    /**
     * Generate Invoice Tagihan PDF (Stream/View)
     */
    public function invoiceTagihanStream(Tagihan $tagihan)
    {
        $tagihan->load(['siswa.waliMurid.user', 'detailTagihan.biaya']);
        
        $pengaturan = $this->getPengaturan();
        
        $pdf = Pdf::loadView('pdf.invoice-tagihan', [
            'tagihan' => $tagihan,
            'pengaturan' => $pengaturan,
        ]);
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('invoice-tagihan-' . $tagihan->siswa->nisn . '-' . $tagihan->bulan . '-' . $tagihan->tahun . '.pdf');
    }

    /**
     * Generate Kwitansi Pembayaran PDF
     */
    public function kwitansi(Pembayaran $pembayaran)
    {
        $pembayaran->load(['tagihan.siswa.waliMurid.user', 'tagihan.detailTagihan.biaya', 'rekeningTujuan', 'dikonfirmasiOleh']);
        
        $pengaturan = $this->getPengaturan();
        
        $pdf = Pdf::loadView('pdf.kwitansi', [
            'pembayaran' => $pembayaran,
            'pengaturan' => $pengaturan,
        ]);
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('kwitansi-' . $pembayaran->id . '-' . date('Ymd') . '.pdf');
    }

    /**
     * Generate Kwitansi Pembayaran PDF (Stream/View)
     */
    public function kwitansiStream(Pembayaran $pembayaran)
    {
        $pembayaran->load(['tagihan.siswa.waliMurid.user', 'tagihan.detailTagihan.biaya', 'rekeningTujuan', 'dikonfirmasiOleh']);
        
        $pengaturan = $this->getPengaturan();
        
        $pdf = Pdf::loadView('pdf.kwitansi', [
            'pembayaran' => $pembayaran,
            'pengaturan' => $pengaturan,
        ]);
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('kwitansi-' . $pembayaran->id . '-' . date('Ymd') . '.pdf');
    }

    /**
     * Generate Kartu SPP PDF
     */
    public function kartuSpp(Siswa $siswa)
    {
        $siswa->load(['waliMurid.user', 'tagihan.detailTagihan.biaya', 'tagihan.pembayaran']);
        
        $pengaturan = $this->getPengaturan();
        
        $pdf = Pdf::loadView('pdf.kartu-spp', [
            'siswa' => $siswa,
            'pengaturan' => $pengaturan,
        ]);
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('kartu-spp-' . $siswa->nisn . '.pdf');
    }

    /**
     * Generate Kartu SPP PDF (Stream/View)
     */
    public function kartuSppStream(Siswa $siswa)
    {
        $siswa->load(['waliMurid.user', 'tagihan.detailTagihan.biaya', 'tagihan.pembayaran']);
        
        $pengaturan = $this->getPengaturan();
        
        $pdf = Pdf::loadView('pdf.kartu-spp', [
            'siswa' => $siswa,
            'pengaturan' => $pengaturan,
        ]);
        
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('kartu-spp-' . $siswa->nisn . '.pdf');
    }

    /**
     * Generate Laporan Pembayaran PDF
     */
    public function laporanPembayaran(Request $request)
    {
        $query = Pembayaran::with(['tagihan.siswa', 'rekeningTujuan'])
            ->where('status_konfirmasi', 'dikonfirmasi');
        
        // Filter by date range
        if ($request->has('dari') && $request->dari) {
            $query->whereDate('tanggal_pembayaran', '>=', $request->dari);
        }
        
        if ($request->has('sampai') && $request->sampai) {
            $query->whereDate('tanggal_pembayaran', '<=', $request->sampai);
        }
        
        $pembayaran = $query->orderBy('tanggal_pembayaran', 'desc')->get();
        
        $pengaturan = $this->getPengaturan();
        
        $pdf = Pdf::loadView('pdf.laporan-pembayaran', [
            'pembayaran' => $pembayaran,
            'pengaturan' => $pengaturan,
            'dari' => $request->dari,
            'sampai' => $request->sampai,
        ]);
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-pembayaran-' . date('Ymd') . '.pdf');
    }

    /**
     * Generate Laporan Tagihan PDF
     */
    public function laporanTagihan(Request $request)
    {
        $query = Tagihan::with(['siswa', 'detailTagihan.biaya']);
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by bulan/tahun
        if ($request->has('bulan') && $request->bulan) {
            $query->where('bulan', $request->bulan);
        }
        
        if ($request->has('tahun') && $request->tahun) {
            $query->where('tahun', $request->tahun);
        }
        
        $tagihan = $query->orderBy('tanggal_tagihan', 'desc')->get();
        
        $pengaturan = $this->getPengaturan();
        
        $pdf = Pdf::loadView('pdf.laporan-tagihan', [
            'tagihan' => $tagihan,
            'pengaturan' => $pengaturan,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'status' => $request->status,
        ]);
        
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-tagihan-' . date('Ymd') . '.pdf');
    }
}
