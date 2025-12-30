@extends('layouts.app')

@section('title', 'Detail Tagihan')

@section('page-title', 'Detail Tagihan')

@section('sidebar-menu')
@include('wali.partials.sidebar-menu', ['active' => 'tagihan'])
@endsection

@section('content')
<div class="space-y-6">
    <!-- Info Tagihan -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Detail Tagihan</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap tagihan</p>
            </div>
            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                {{ $tagihan->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($tagihan->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Siswa</label>
                <p class="text-gray-900 mt-1 font-medium">{{ $tagihan->siswa->nama }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">NISN</label>
                <p class="text-gray-900 mt-1">{{ $tagihan->siswa->nisn }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Periode</label>
                <p class="text-gray-900 mt-1">{{ $tagihan->bulan }} / {{ $tagihan->tahun }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Tanggal Tagihan</label>
                <p class="text-gray-900 mt-1">{{ $tagihan->tanggal_tagihan->format('d F Y') }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Total Tagihan</label>
                <p class="text-gray-900 mt-1 text-lg font-semibold">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Jumlah Dibayar</label>
                <p class="text-gray-900 mt-1 text-lg font-semibold text-green-600">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</p>
            </div>

            <div class="col-span-2">
                <label class="text-sm font-medium text-gray-500">Sisa Tagihan</label>
                <p class="text-gray-900 mt-1 text-2xl font-bold text-red-600">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Detail Biaya -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Biaya</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tagihan->detailTagihan as $index => $detail)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->biaya->nama_biaya }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada detail biaya</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tagihan->pembayaran as $index => $bayar)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bayar->tanggal_pembayaran?->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $bayar->status_konfirmasi === 'dikonfirmasi' ? 'bg-green-100 text-green-800' : ($bayar->status_konfirmasi === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($bayar->status_konfirmasi) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('wali.tagihan.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 inline-flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>

        @if($tagihan->status !== 'lunas')
        <a href="{{ route('wali.pembayaran.konfirmasi', $tagihan) }}" 
           class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-700 inline-flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Bayar Sekarang</span>
        </a>
        @endif
    </div>
</div>
@endsection
