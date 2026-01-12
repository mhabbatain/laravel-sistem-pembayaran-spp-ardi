<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobTagihan;
use App\Models\Siswa;
use App\Models\Biaya;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobTagihanController extends Controller
{
    public function index()
    {
        $jobs = JobTagihan::with('createdBy')->latest()->paginate(10);
        return view('admin.job-tagihan.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.job-tagihan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|string',
            'tahun' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        // Check if job already exists for this bulan/tahun
        $exists = JobTagihan::where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Job tagihan untuk periode yang sama sudah ada.');
        }

        // Get all active siswa
        $siswaList = Siswa::where('is_active', true)->get();
        $totalSiswa = $siswaList->count();

        if ($totalSiswa == 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tidak ada siswa aktif untuk dibuatkan tagihan.');
        }

        // Get all default biaya
        $biayaList = Biaya::where('is_default', true)->get();

        if ($biayaList->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tidak ada biaya default yang dikonfigurasi. Silakan tambahkan biaya terlebih dahulu.');
        }

        // Create job record
        $job = JobTagihan::create([
            'modul_job' => 'ProcessTagihan',
            'progres' => 0,
            'total' => $totalSiswa,
            'status' => 'processing',
            'deskripsi' => $request->deskripsi,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'created_by' => auth()->id(),
        ]);

        // Process tagihan for each siswa
        $processed = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($siswaList as $siswa) {
                // Check if tagihan already exists for this siswa, bulan, tahun
                $tagihanExists = Tagihan::where('siswa_id', $siswa->id)
                    ->where('bulan', $request->bulan)
                    ->where('tahun', $request->tahun)
                    ->exists();

                if ($tagihanExists) {
                    $skipped++;
                    $job->update(['progres' => $processed + $skipped]);
                    continue;
                }

                // Calculate total tagihan from default biaya
                $totalTagihan = $biayaList->sum('jumlah');

                // Create tagihan
                $tagihan = Tagihan::create([
                    'siswa_id' => $siswa->id,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'tanggal_tagihan' => now(),
                    'status' => 'baru',
                    'total_tagihan' => $totalTagihan,
                    'jumlah_bayar' => 0,
                    'sisa_tagihan' => $totalTagihan,
                    'created_by' => auth()->id(),
                ]);

                // Create detail tagihan for each biaya
                foreach ($biayaList as $biaya) {
                    DetailTagihan::create([
                        'tagihan_id' => $tagihan->id,
                        'biaya_id' => $biaya->id,
                        'jumlah' => $biaya->jumlah,
                        'is_selected' => true,
                    ]);
                }

                $processed++;
                $job->update(['progres' => $processed + $skipped]);
            }

            // Mark job as completed
            $job->update([
                'status' => 'completed',
                'progres' => $totalSiswa,
            ]);

            DB::commit();

            $message = "Job tagihan berhasil diproses. {$processed} tagihan dibuat";
            if ($skipped > 0) {
                $message .= ", {$skipped} dilewati (sudah ada).";
            } else {
                $message .= ".";
            }

            return redirect()->route('admin.job-tagihan.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $job->update([
                'status' => 'failed',
            ]);

            return redirect()->route('admin.job-tagihan.index')
                ->with('error', 'Terjadi kesalahan saat memproses job: ' . $e->getMessage());
        }
    }

    public function show(JobTagihan $jobTagihan)
    {
        $jobTagihan->load('createdBy');
        return view('admin.job-tagihan.show', compact('jobTagihan'));
    }

    public function destroy(JobTagihan $jobTagihan)
    {
        // Only allow deletion if status is completed or failed
        if (!in_array($jobTagihan->status, ['completed', 'failed'])) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus job yang sedang berjalan.');
        }

        $jobTagihan->delete();

        return redirect()->route('admin.job-tagihan.index')
            ->with('success', 'Job tagihan berhasil dihapus.');
    }
}
