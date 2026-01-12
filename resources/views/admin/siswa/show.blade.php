@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('page-title', 'Detail Siswa')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'siswa'])
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informasi Siswa -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Informasi Siswa</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.pdf.kartu-spp', $siswa) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Kartu SPP</span>
                </a>
                <a href="{{ route('admin.siswa.edit', $siswa->id) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('admin.siswa.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">NISN</label>
                <p class="text-base font-semibold text-gray-800">{{ $siswa->nisn }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                <p class="text-base font-semibold text-gray-800">{{ $siswa->nama }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                <p class="text-base text-gray-800">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Jurusan</label>
                <p class="text-base text-gray-800">{{ $siswa->jurusan }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Alamat</label>
                <p class="text-base text-gray-800">{{ $siswa->alamat }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Kelas</label>
                <p class="text-base text-gray-800">{{ $siswa->kelas }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Wali Murid</label>
                <p class="text-base text-gray-800">{{ $siswa->waliMurid->user->name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                <span
                    class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $siswa->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $siswa->is_active ? 'Aktif' : 'Non-Aktif' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Riwayat Tagihan -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Riwayat Tagihan</h2>

        @if($siswa->tagihan && $siswa->tagihan->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($siswa->tagihan as $index => $tagihan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::createFromDate($tagihan->tahun, $tagihan->bulan,
                            1)->locale('id')->translatedFormat('F') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tagihan->tahun }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{
                            number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{
                            number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tagihan->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($tagihan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.tagihan.show', $tagihan->id) }}"
                                class="text-green-600 hover:text-green-900">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Tagihan -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
                <p class="text-xl font-bold text-gray-800">Rp {{ number_format($siswa->tagihan->sum('jumlah'), 0, ',',
                    '.') }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Total Dibayar</p>
                <p class="text-xl font-bold text-green-800">Rp {{ number_format($siswa->tagihan->sum('jumlah_bayar'), 0,
                    ',', '.') }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Total Tunggakan</p>
                <p class="text-xl font-bold text-red-800">Rp {{ number_format($siswa->tagihan->sum('jumlah') -
                    $siswa->tagihan->sum('jumlah_bayar'), 0, ',', '.') }}</p>
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="mt-2 text-gray-500">Belum ada tagihan untuk siswa ini</p>
        </div>
        @endif
    </div>
</div>
@endsection