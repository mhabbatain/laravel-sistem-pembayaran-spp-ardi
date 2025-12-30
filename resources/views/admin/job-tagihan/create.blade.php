@extends('layouts.app')

@section('title', 'Buat Job Tagihan')

@section('page-title', 'Buat Job Tagihan')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'job-tagihan'])
@endsection

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Form Buat Job Tagihan</h2>
            <p class="text-sm text-gray-600 mt-1">Job ini akan membuat tagihan secara otomatis untuk semua siswa pada periode yang ditentukan</p>
        </div>

        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Informasi</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Job ini akan membuat tagihan untuk semua siswa aktif</li>
                            <li>Tagihan akan dibuat berdasarkan biaya yang sudah dikonfigurasi</li>
                            <li>Proses akan berjalan di background dan dapat memakan waktu</li>
                            <li>Anda akan melihat progress pada halaman riwayat job</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.job-tagihan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Bulan -->
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">
                    Bulan <span class="text-red-500">*</span>
                </label>
                <select name="bulan" id="bulan" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('bulan') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Bulan</option>
                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                    <option value="{{ $b }}" {{ old('bulan') == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
                @error('bulan')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                    Tahun <span class="text-red-500">*</span>
                </label>
                <select name="tahun" id="tahun" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('tahun') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Tahun</option>
                    @php
                        $currentYear = date('Y');
                        $startYear = $currentYear - 2;
                        $endYear = $currentYear + 2;
                    @endphp
                    @for($year = $endYear; $year >= $startYear; $year--)
                    <option value="{{ $year }}" {{ old('tahun', $currentYear) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
                @error('tahun')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="4" required
                    placeholder="Contoh: Tagihan SPP bulan Januari 2025 untuk semua siswa kelas 1-6"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('deskripsi') ? 'border-red-500' : 'border-gray-300' }}">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('admin.job-tagihan.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Buat dan Proses Job
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
