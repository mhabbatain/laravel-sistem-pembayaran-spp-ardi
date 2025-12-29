@extends('layouts.app')

@section('title', 'Kartu SPP')

@section('page-title', 'Kartu SPP - ' . $siswa->nama)

@section('sidebar-menu')
@include('wali.partials.sidebar-menu', ['active' => 'siswa'])
@endsection

@section('content')
<div class="space-y-6">
    <!-- Siswa Info -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $siswa->nama }}</h2>
                <p class="text-gray-600">NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Tagihan</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($siswa->tagihan->sum('total_tagihan'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Sudah Dibayar</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($siswa->tagihan->sum('jumlah_bayar'), 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Sisa Tagihan</p>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($siswa->tagihan->sum('sisa_tagihan'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Tagihan List -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Tagihan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tagihan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa->tagihan as $tagihan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $tagihan->bulan }}/{{ $tagihan->tahun }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $tagihan->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($tagihan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('wali.tagihan.show', $tagihan) }}" class="text-green-600 hover:text-green-900">Detail</a>
                            @if($tagihan->status !== 'lunas')
                            <a href="{{ route('wali.pembayaran.konfirmasi', $tagihan) }}" class="text-green-600 hover:text-green-900">Bayar</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada tagihan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('wali.siswa.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 inline-flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>
</div>
@endsection
