@extends('layouts.app')

@section('title', 'Detail Santri')

@section('page-title', 'Detail Santri')

@section('sidebar-menu')
@include('superadmin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('superadmin.siswa.index') }}"
            class="inline-flex items-center text-green-600 hover:text-green-800">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Data Santri
        </a>
    </div>

    <!-- Student Info -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Santri</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <table class="w-full">
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium w-1/3">NISN</td>
                        <td class="py-2 text-gray-900">{{ $siswa->nisn }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium">Nama</td>
                        <td class="py-2 text-gray-900">{{ $siswa->nama }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium">Jenis Kelamin</td>
                        <td class="py-2 text-gray-900">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium">Kelas</td>
                        <td class="py-2 text-gray-900">{{ $siswa->kelas }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="w-full">
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium w-1/3">Jurusan</td>
                        <td class="py-2 text-gray-900">{{ $siswa->jurusan }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium">Angkatan</td>
                        <td class="py-2 text-gray-900">{{ $siswa->angkatan }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium">Wali Santri</td>
                        <td class="py-2 text-gray-900">{{ $siswa->waliMurid?->user?->name ?? '-' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium">Status</td>
                        <td class="py-2">
                            @if($siswa->is_active)
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                            @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Non-Aktif
                            </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Tagihan History -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Tagihan (12 Bulan Terakhir)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            Tagihan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Terbayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa->tagihan as $tagihan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::create()->month($tagihan->bulan)->translatedFormat('F') }} {{
                            $tagihan->tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tagihan->status == 'lunas')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Lunas
                            </span>
                            @elseif($tagihan->status == 'cicilan')
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Cicilan
                            </span>
                            @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Belum Bayar
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data tagihan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection