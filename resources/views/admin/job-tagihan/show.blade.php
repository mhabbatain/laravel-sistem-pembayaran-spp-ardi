@extends('layouts.app')

@section('title', 'Detail Job Tagihan')

@section('page-title', 'Detail Job Tagihan')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'job-tagihan'])
@endsection

@section('content')
<div class="space-y-6">
    <!-- Job Info -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Detail Job Tagihan</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap job pembuatan tagihan</p>
            </div>
            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                {{ $jobTagihan->status === 'completed' ? 'bg-green-100 text-green-800' : ($jobTagihan->status === 'processing' ? 'bg-green-100 text-green-800' : ($jobTagihan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                {{ ucfirst($jobTagihan->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Modul Job</label>
                <p class="text-gray-900 mt-1">{{ $jobTagihan->modul_job }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Periode</label>
                <p class="text-gray-900 mt-1 font-medium">{{ $jobTagihan->bulan }} / {{ $jobTagihan->tahun }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Dibuat Oleh</label>
                <p class="text-gray-900 mt-1">{{ $jobTagihan->createdBy->name ?? '-' }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Tanggal Dibuat</label>
                <p class="text-gray-900 mt-1">{{ $jobTagihan->created_at->format('d F Y, H:i') }}</p>
            </div>

            <div class="col-span-2">
                <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                <p class="text-gray-900 mt-1">{{ $jobTagihan->deskripsi }}</p>
            </div>
        </div>
    </div>

    <!-- Progress -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Pemrosesan</h3>
        
        <div class="space-y-4">
            <div class="flex justify-between text-sm text-gray-600">
                <span>{{ $jobTagihan->progres }} / {{ $jobTagihan->total }} tagihan diproses</span>
                <span>{{ $jobTagihan->progress_percentage }}%</span>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-green-700 h-4 rounded-full transition-all duration-300" 
                     style="width: {{ $jobTagihan->progress_percentage }}%"></div>
            </div>

            @if($jobTagihan->status === 'processing')
            <p class="text-sm text-green-600 flex items-center">
                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Job sedang diproses...
            </p>
            @elseif($jobTagihan->status === 'completed')
            <p class="text-sm text-green-600 flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Job selesai diproses
            </p>
            @elseif($jobTagihan->status === 'failed')
            <p class="text-sm text-red-600 flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Job gagal diproses
            </p>
            @else
            <p class="text-sm text-yellow-600 flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Job menunggu untuk diproses
            </p>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.job-tagihan.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 inline-flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>

        @if(in_array($jobTagihan->status, ['completed', 'failed']))
        <form action="{{ route('admin.job-tagihan.destroy', $jobTagihan) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Yakin ingin menghapus job ini?')"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Hapus Job
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
