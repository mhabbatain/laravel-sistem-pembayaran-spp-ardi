@extends('layouts.app')

@section('title', 'Detail Wali Murid')

@section('page-title', 'Detail Wali Murid')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'wali-murid'])
@endsection

@section('content')
<div class="space-y-6">
    <!-- Info Wali Murid -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Informasi Wali Murid</h2>
                <p class="text-sm text-gray-600 mt-1">Detail data wali murid</p>
            </div>
            <a href="{{ route('admin.wali-murid.edit', $waliMurid) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                <p class="text-gray-900 mt-1">{{ $waliMurid->user->name }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Email</label>
                <p class="text-gray-900 mt-1">{{ $waliMurid->user->email }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">No. HP/WhatsApp</label>
                <p class="text-gray-900 mt-1">{{ $waliMurid->user->no_hp ?? '-' }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Jumlah Anak</label>
                <p class="text-gray-900 mt-1">{{ $waliMurid->siswa->count() }} Siswa</p>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-500">Terdaftar Sejak</label>
                <p class="text-gray-900 mt-1">{{ $waliMurid->created_at->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Daftar Siswa/Anak -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Siswa/Anak</h2>
        </div>

        @if($waliMurid->siswa->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($waliMurid->siswa as $index => $siswa)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $siswa->nisn }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $siswa->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $siswa->kelas }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $siswa->jurusan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $siswa->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $siswa->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.siswa.show', $siswa) }}" class="text-green-600 hover:text-green-900">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">Belum ada siswa terdaftar untuk wali murid ini</p>
        </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('admin.wali-murid.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 inline-flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>
</div>
@endsection
