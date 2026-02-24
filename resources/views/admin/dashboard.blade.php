@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu', ['active' => 'dashboard'])
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Selamat Datang, {{ Auth::user()->name }}</h2>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Siswa Aktif -->
        <a href="{{ route('admin.siswa.index') }}"
            class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Siswa Aktif</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $totalSiswaAktif }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total Uang Kas -->
        <a href="{{ route('admin.pembayaran.index') }}"
            class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Uang Kas</dt>
                            <dd class="text-3xl font-semibold text-gray-900">Rp {{ number_format($totalUangKas, 0, ',',
                                '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </a>

        <!-- Pesan Baru -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pesan Baru</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $pesanBaru }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tagihan Baru -->
        <a href="{{ route('admin.tagihan.index') }}"
            class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tagihan Baru</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $tagihanBaru }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection