@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Biaya</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.biaya.update', $biaya->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nama_biaya" class="block text-sm font-medium text-gray-700 mb-2">Nama Biaya</label>
                <input type="text" name="nama_biaya" id="nama_biaya" value="{{ old('nama_biaya', $biaya->nama_biaya) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('nama_biaya') ? 'border-red-500' : 'border-gray-300' }}">
                @error('nama_biaya')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                <input type="text" name="kode" id="kode" value="{{ old('kode', $biaya->kode) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('kode') ? 'border-red-500' : 'border-gray-300' }}">
                @error('kode')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $biaya->jumlah) }}" required min="0" step="1000"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('jumlah') ? 'border-red-500' : 'border-gray-300' }}">
                @error('jumlah')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('keterangan') ? 'border-red-500' : 'border-gray-300' }}">{{ old('keterangan', $biaya->keterangan) }}</textarea>
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
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection