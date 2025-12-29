@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Biaya Baru</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.biaya.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nama_biaya" class="block text-sm font-medium text-gray-700 mb-2">Nama Biaya</label>
                <input type="text" name="nama_biaya" id="nama_biaya" value="{{ old('nama_biaya') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('nama_biaya') border-red-500 @enderror">
                @error('nama_biaya')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                <input type="text" name="kode" id="kode" value="{{ old('kode') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('kode') border-red-500 @enderror">
                @error('kode')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 0) }}" required min="0" step="1000"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('jumlah') border-red-500 @enderror">
                @error('jumlah')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.biaya.index') }}"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection