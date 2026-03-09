@extends('layouts.app')

@section('title', 'Tambah Tagihan')

@section('page-title', 'Tambah Tagihan')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'tagihan'])
@endsection

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Form Tambah Tagihan</h2>
            <p class="text-sm text-gray-600 mt-1">Lengkapi formulir di bawah untuk membuat tagihan baru</p>
        </div>

        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <form action="{{ route('admin.tagihan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Siswa -->
            <div>
                <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Siswa <span class="text-red-500">*</span>
                </label>
                <select name="siswa_id" id="siswa_id" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('siswa_id') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Siswa</option>
                    @foreach($siswa as $item)
                    <option value="{{ $item->id }}" {{ old('siswa_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }} - {{ $item->nisn }} (Wali: {{ $item->waliMurid->user->name ?? '-' }})
                    </option>
                    @endforeach
                </select>
                @error('siswa_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

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

            <!-- Tanggal Tagihan -->
            <div>
                <label for="tanggal_tagihan" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Tagihan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_tagihan" id="tanggal_tagihan" 
                    value="{{ old('tanggal_tagihan', date('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('tanggal_tagihan') ? 'border-red-500' : 'border-gray-300' }}">
                @error('tanggal_tagihan')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rincian Biaya (Otomatis) -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Rincian Biaya <span class="text-xs font-normal text-gray-500">(Otomatis Terpilih Semua)</span>
                </label>
                <div class="divide-y divide-gray-200">
                    @foreach($biaya as $item)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <div>
                                <span class="font-medium text-gray-900">{{ $item->nama_biaya }}</span>
                                @if($item->kode)
                                <span class="text-xs text-gray-500">({{ $item->kode }})</span>
                                @endif
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <p class="mt-4 text-xs text-blue-600 italic">* Semua biaya di atas akan otomatis disertakan dalam tagihan ini. Wali santri dapat memilih komponen yang ingin dibayar pada saat melakukan konfirmasi pembayaran.</p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('admin.tagihan.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Simpan Tagihan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
