@extends('layouts.app')

@section('title', 'Detail Wali Santri')

@section('page-title', 'Detail Wali Santri')

@section('sidebar-menu')
@include('superadmin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('superadmin.wali-murid.index') }}"
            class="inline-flex items-center text-green-600 hover:text-green-800">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Data Wali Santri
        </a>
    </div>

    <!-- Wali Info -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Wali Santri</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <table class="w-full">
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium w-1/3">Nama</td>
                        <td class="py-2 text-gray-900">{{ $waliMurid->user?->name ?? '-' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium">Email</td>
                        <td class="py-2 text-gray-900">{{ $waliMurid->user?->email ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="w-full">
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium w-1/3">No. HP</td>
                        <td class="py-2 text-gray-900">{{ $waliMurid->user?->no_hp ?? '-' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-600 font-medium">Terdaftar</td>
                        <td class="py-2 text-gray-900">{{ $waliMurid->created_at->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Daftar Santri -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Santri</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tagihan Aktif</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($waliMurid->siswa as $index => $siswa)
                    @php
                    $tagihanBelumLunas = $siswa->tagihan->where('status', '!=', 'lunas')->count();
                    $totalSisaTagihan = $siswa->tagihan->sum('sisa_tagihan');
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $siswa->nisn }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('superadmin.siswa.show', $siswa) }}"
                                class="text-green-600 hover:text-green-800">
                                {{ $siswa->nama }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $siswa->kelas }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($tagihanBelumLunas > 0)
                            <span class="text-red-600 font-medium">
                                {{ $tagihanBelumLunas }} tagihan (Rp {{ number_format($totalSisaTagihan, 0, ',', '.')
                                }})
                            </span>
                            @else
                            <span class="text-green-600 font-medium">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Belum ada santri terdaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection