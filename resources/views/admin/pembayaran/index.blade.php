@extends('layouts.app')

@section('title', 'Data Pembayaran')

@section('page-title', 'Data Pembayaran')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'pembayaran'])
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Pembayaran</h2>
            <a href="{{ route('admin.pdf.laporan-pembayaran', request()->query()) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export PDF</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pembayaran as $index => $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pembayaran->firstItem() +
                            $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                            $item->tanggal_pembayaran?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                            $item->tagihan->siswa->nama ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{
                            number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $item->status_konfirmasi === 'dikonfirmasi' ? 'bg-green-100 text-green-800' : ($item->status_konfirmasi === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($item->status_konfirmasi) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.pembayaran.show', $item->id) }}"
                                class="text-green-600 hover:text-green-900">Detail</a>
                            @if ($item->status_konfirmasi === 'pending')
                            <form action="{{ route('admin.pembayaran.konfirmasi', $item->id) }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900">Konfirmasi</button>
                            </form>
                            <form action="{{ route('admin.pembayaran.tolak', $item->id) }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Yakin ingin menolak?')">Tolak</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pembayaran->links() }}
        </div>
    </div>
</div>
@endsection