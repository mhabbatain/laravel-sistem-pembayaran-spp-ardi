@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Detail Pembayaran</h1>
        <a href="{{ route('admin.pembayaran.index') }}" class="text-green-600 hover:text-green-800">
            ← Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Pembayaran -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Informasi Pembayaran</h2>

            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Tanggal Pembayaran</span>
                    <span class="font-medium">{{ $pembayaran->tanggal_pembayaran?->format('d F Y') ?? '-' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Nama Siswa</span>
                    <span class="font-medium">{{ $pembayaran->tagihan->siswa->nama ?? '-' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">NISN</span>
                    <span class="font-medium">{{ $pembayaran->tagihan->siswa->nisn ?? '-' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Wali Murid</span>
                    <span class="font-medium">{{ $pembayaran->waliMurid->user->name ?? '-' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Periode Tagihan</span>
                    <span class="font-medium">{{ $pembayaran->tagihan->bulan ?? '-' }} / {{ $pembayaran->tagihan->tahun ?? '-' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Total Tagihan</span>
                    <span class="font-medium text-red-600">Rp {{ number_format($pembayaran->tagihan->total_tagihan ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Jumlah Dibayar</span>
                    <span class="font-medium text-green-600">Rp {{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span class="font-medium">{{ ucfirst($pembayaran->metode_pembayaran ?? '-') }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Rekening Tujuan</span>
                    <span class="font-medium">
                        {{ $pembayaran->rekeningTujuan->nama_bank ?? '-' }} -
                        {{ $pembayaran->rekeningTujuan->nomor_rekening ?? '-' }}
                    </span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Status</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $pembayaran->status_konfirmasi === 'dikonfirmasi' ? 'bg-green-100 text-green-800' : 
                           ($pembayaran->status_konfirmasi === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($pembayaran->status_konfirmasi ?? '-') }}
                    </span>
                </div>

                @if($pembayaran->status_konfirmasi === 'dikonfirmasi')
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Tanggal Konfirmasi</span>
                    <span class="font-medium">{{ $pembayaran->tanggal_konfirmasi?->format('d F Y') ?? '-' }}</span>
                </div>

                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Dikonfirmasi Oleh</span>
                    <span class="font-medium">{{ $pembayaran->dikonfirmasiOleh->name ?? '-' }}</span>
                </div>
                @endif

                @if($pembayaran->catatan)
                <div class="border-b pb-2">
                    <span class="text-gray-600 block mb-1">Catatan</span>
                    <p class="text-gray-800">{{ $pembayaran->catatan }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Bukti Pembayaran -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Bukti Pembayaran</h2>

            @if($pembayaran->bukti_pembayaran)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                    alt="Bukti Pembayaran"
                    class="w-full rounded-lg border"
                    onerror="this.src='https://via.placeholder.com/400x300?text=Bukti+Pembayaran'">
            </div>
            <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                target="_blank"
                class="block text-center px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-700 transition">
                Lihat Ukuran Penuh
            </a>
            @else
            <p class="text-gray-500 text-center py-8">Tidak ada bukti pembayaran</p>
            @endif

            @if($pembayaran->status_konfirmasi === 'pending')
            <div class="mt-6 space-y-3">
                <form action="{{ route('admin.pembayaran.konfirmasi', $pembayaran->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-700 transition"
                        onclick="return confirm('Yakin ingin mengkonfirmasi pembayaran ini?')">
                        ✓ Konfirmasi Pembayaran
                    </button>
                </form>

                <form action="{{ route('admin.pembayaran.tolak', $pembayaran->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                        onclick="return confirm('Yakin ingin menolak pembayaran ini?')">
                        ✗ Tolak Pembayaran
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection