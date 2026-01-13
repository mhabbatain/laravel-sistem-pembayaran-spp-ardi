@extends('layouts.app')

@section('title', 'Laporan Tagihan')

@section('page-title', 'Laporan Tagihan')

@section('sidebar-menu')
@include('superadmin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Laporan Tagihan</h2>
                <p class="text-sm text-gray-500">Daftar semua tagihan santri</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Tagihan</p>
                <p class="text-xl font-bold text-gray-900">Rp {{ number_format($summary['total_tagihan'], 0, ',', '.')
                    }}
                </p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Terbayar</p>
                <p class="text-xl font-bold text-green-600">Rp
                    {{ number_format($summary['total_terbayar'], 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Total Sisa</p>
                <p class="text-xl font-bold text-red-600">Rp {{ number_format($summary['total_sisa'], 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Jumlah Lunas</p>
                <p class="text-xl font-bold text-green-600">{{ $summary['jumlah_lunas'] }}</p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">Belum Lunas</p>
                <p class="text-xl font-bold text-red-600">{{ $summary['jumlah_belum_lunas'] }}</p>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="mb-6">
            <form method="GET" action="{{ route('superadmin.laporan.tagihan') }}" class="space-y-3">
                <div class="flex flex-wrap gap-3">
                    <div>
                        <select name="status"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="baru" {{ request('status')=='baru' ? 'selected' : '' }}>Baru</option>
                            <option value="cicilan" {{ request('status')=='cicilan' ? 'selected' : '' }}>Cicilan
                            </option>
                            <option value="lunas" {{ request('status')=='lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <div>
                        <select name="bulan"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Bulan</option>
                            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                            'September', 'Oktober', 'November', 'Desember'] as $bulan)
                            <option value="{{ $bulan }}" {{ request('bulan')==$bulan ? 'selected' : '' }}>{{ $bulan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="tahun"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunList as $t)
                            <option value="{{ $t }}" {{ request('tahun')==$t ? 'selected' : '' }}>
                                {{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="kelas"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $k)
                            <option value="{{ $k }}" {{ request('kelas')==$k ? 'selected' : '' }}>
                                {{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filter</span>
                    </button>
                    @if (request()->hasAny(['status', 'bulan', 'tahun', 'kelas']))
                    <a href="{{ route('superadmin.laporan.tagihan') }}"
                        class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 inline-flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Reset</span>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Santri</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Tagihan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Terbayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sisa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tagihan as $index => $t)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $tagihan->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $t->siswa?->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $t->siswa?->kelas ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $t->bulan }} {{ $t->tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                            Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                            Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($t->status == 'lunas')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Lunas
                            </span>
                            @elseif($t->status == 'cicilan')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Cicilan
                            </span>
                            @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Baru
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data tagihan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tagihan->links() }}
        </div>
    </div>
</div>
@endsection