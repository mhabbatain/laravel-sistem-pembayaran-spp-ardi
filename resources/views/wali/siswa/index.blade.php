@extends('layouts.app')

@section('title', 'Data Siswa')

@section('page-title', 'Data Siswa')

@section('sidebar-menu')
@include('wali.partials.sidebar-menu', ['active' => 'siswa'])
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Anak</h2>
            <p class="text-sm text-gray-600 mt-1">Data anak yang terdaftar</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($siswa as $item)
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $item->nama }}</h3>
                            <p class="text-sm text-gray-500">NISN: {{ $item->nisn }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Kelas</span>
                        <span class="font-medium text-gray-900">{{ $item->kelas ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tanggal Lahir</span>
                        <span class="font-medium text-gray-900">{{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jenis Kelamin</span>
                        <span class="font-medium text-gray-900">{{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('wali.siswa.kartu-spp', $item) }}" 
                       class="flex-1 px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 inline-flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Kartu SPP</span>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p class="mt-4 text-gray-500">Tidak ada data siswa</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
