@extends('layouts.app')

@section('title', 'Edit Tagihan')

@section('page-title', 'Edit Tagihan')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'tagihan'])
@endsection

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Form Edit Tagihan</h2>
            <p class="text-sm text-gray-600 mt-1">Perbarui informasi tagihan</p>
        </div>

        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        @if($tagihan->jumlah_bayar > 0)
        <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded relative" role="alert">
            <p class="font-medium">Perhatian!</p>
            <p class="text-sm">Tagihan ini sudah memiliki pembayaran sebesar Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}. Perubahan biaya akan mempengaruhi sisa tagihan.</p>
        </div>
        @endif

        <form action="{{ route('admin.tagihan.update', $tagihan) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Siswa -->
            <div>
                <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Siswa <span class="text-red-500">*</span>
                </label>
                <select name="siswa_id" id="siswa_id" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('siswa_id') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Siswa</option>
                    @foreach($siswa as $item)
                    <option value="{{ $item->id }}" {{ old('siswa_id', $tagihan->siswa_id) == $item->id ? 'selected' : '' }}>
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
                    <option value="{{ $b }}" {{ old('bulan', $tagihan->bulan) == $b ? 'selected' : '' }}>{{ $b }}</option>
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
                    <option value="{{ $year }}" {{ old('tahun', $tagihan->tahun) == $year ? 'selected' : '' }}>{{ $year }}</option>
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
                    value="{{ old('tanggal_tagihan', $tagihan->tanggal_tagihan->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('tanggal_tagihan') ? 'border-red-500' : 'border-gray-300' }}">
                @error('tanggal_tagihan')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Biaya (Checkboxes) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Pilih Biaya <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2 border {{ $errors->has('biaya_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-4 max-h-64 overflow-y-auto">
                    @foreach($biaya as $item)
                    @php
                        $isChecked = is_array(old('biaya_id')) 
                            ? in_array($item->id, old('biaya_id')) 
                            : in_array($item->id, $selectedBiaya);
                    @endphp
                    <label class="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="biaya_id[]" value="{{ $item->id }}" 
                                {{ $isChecked ? 'checked' : '' }}
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <div>
                                <span class="font-medium text-gray-900">{{ $item->nama_biaya }}</span>
                                @if($item->kode)
                                <span class="text-xs text-gray-500">({{ $item->kode }})</span>
                                @endif
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                    </label>
                    @endforeach
                </div>
                @error('biaya_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                @error('biaya_id.*')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Pembayaran -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Total Tagihan Saat Ini</p>
                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Jumlah Dibayar</p>
                        <p class="text-lg font-semibold text-green-600">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Sisa Tagihan</p>
                        <p class="text-lg font-semibold text-red-600">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('admin.tagihan.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Perbarui Tagihan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
