@extends('layouts.app')

@section('title', 'Data Santri')

@section('page-title', 'Data Santri')

@section('sidebar-menu')
@include('wali.partials.sidebar-menu', ['active' => 'siswa'])
@endsection

@section('content')
<div class="space-y-6">
    @forelse($siswa as $item)
    @php
    // Collect all biaya from all tagihan for this santri
    $allBiaya = collect();
    foreach($item->tagihan as $tagihan) {
    foreach($tagihan->detailTagihan as $detail) {
    $key = $detail->biaya_id;
    if(!$allBiaya->has($key)) {
    $allBiaya->put($key, [
    'nama' => $detail->biaya->nama_biaya ?? '-',
    'jumlah' => $detail->jumlah
    ]);
    }
    }
    }
    $totalTagihan = $allBiaya->sum('jumlah');
    @endphp
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $item->nama }}</h2>
                    <p class="text-sm text-gray-500">NISN: {{ $item->nisn }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                    {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
                <a href="{{ route('wali.siswa.kartu-spp', $item) }}"
                    class="px-4 py-2 bg-green-700 text-white text-sm rounded-lg hover:bg-green-800 inline-flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Kartu SPP</span>
                </a>
            </div>
        </div>

        <!-- Detail Santri -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Detail Santri</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50 w-1/4">ID</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->id }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50">Nama</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nama }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50">NISN</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50">Jenis Kelamin</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->jenis_kelamin === 'L' ? 'Laki-laki' :
                                'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50">Program Studi</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->jurusan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50">Alamat</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50">Tanggal Buat</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->created_at ?
                                $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50">Tanggal Ubah</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->updated_at ?
                                $item->updated_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-500 bg-gray-50">Dibuat Oleh</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->createdBy->name ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Biaya -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Rincian Biaya</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                No</th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Biaya</th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allBiaya as $index => $biaya)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $biaya['nama'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">Rp {{ number_format($biaya['jumlah'],
                                0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-center">Belum ada data biaya
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900">Total Tagihan</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Rp {{
                                number_format($totalTagihan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <p class="mt-4 text-gray-500">Tidak ada data santri</p>
        </div>
    </div>
    @endforelse
</div>
@endsection