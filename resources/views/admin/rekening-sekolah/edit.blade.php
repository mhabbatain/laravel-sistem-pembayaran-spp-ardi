@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Rekening Sekolah</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.rekening-sekolah.update', $rekeningSekolah->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="kode_transfer" class="block text-sm font-medium text-gray-700 mb-2">Kode Transfer</label>
                <input type="text" name="kode_transfer" id="kode_transfer" value="{{ old('kode_transfer', $rekeningSekolah->kode_transfer) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('kode_transfer') border-red-500 @enderror">
                @error('kode_transfer')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank', $rekeningSekolah->nama_bank) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('nama_bank') border-red-500 @enderror">
                @error('nama_bank')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="pemilik_rekening" class="block text-sm font-medium text-gray-700 mb-2">Pemilik Rekening</label>
                <input type="text" name="pemilik_rekening" id="pemilik_rekening" value="{{ old('pemilik_rekening', $rekeningSekolah->pemilik_rekening) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('pemilik_rekening') border-red-500 @enderror">
                @error('pemilik_rekening')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening', $rekeningSekolah->nomor_rekening) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('nomor_rekening') border-red-500 @enderror">
                @error('nomor_rekening')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $rekeningSekolah->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Status Aktif</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.rekening-sekolah.index') }}"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection