<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobTagihan;
use Illuminate\Http\Request;

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

        // Count total siswa that will be processed
        $totalSiswa = \App\Models\Siswa::count();

        $job = JobTagihan::create([
            'modul_job' => 'ProcessTagihan',
            'progres' => 0,
            'total' => $totalSiswa,
            'status' => 'pending',
            'deskripsi' => $request->deskripsi,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'created_by' => auth()->id(),
        ]);

        // TODO: Dispatch job to process tagihan in background
        // ProcessTagihanJob::dispatch($job->id);

        return redirect()->route('admin.job-tagihan.index')
            ->with('success', 'Job tagihan berhasil dibuat dan akan diproses.');
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
