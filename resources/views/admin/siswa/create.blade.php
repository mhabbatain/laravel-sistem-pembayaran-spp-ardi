@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('page-title', 'Tambah Siswa')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'siswa'])
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Siswa Baru</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="wali_murid_id" class="block text-sm font-medium text-gray-700 mb-2">Wali Murid</label>
                    <select name="wali_murid_id" id="wali_murid_id"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('wali_murid_id') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Wali Murid</option>
                        @foreach($waliMurid as $wali)
                        <option value="{{ $wali->id }}" {{ old('wali_murid_id')==$wali->id ? 'selected' : '' }}>
                            {{ $wali->user->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('wali_murid_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('nisn') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('nisn')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis
                        Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('jenis_kelamin') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin')=='L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin')=='P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <select name="jurusan" id="jurusan" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('jurusan') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Jurusan</option>
                        <option value="IPA" {{ old('jurusan')=='IPA' ? 'selected' : '' }}>IPA</option>
                        <option value="IPS" {{ old('jurusan')=='IPS' ? 'selected' : '' }}>IPS</option>
                    </select>
                    @error('jurusan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('alamat') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                    @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas" id="kelas" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('kelas') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Kelas</option>
                        <option value="VII" {{ old('kelas')=='VII' ? 'selected' : '' }}>VII</option>
                        <option value="VIII" {{ old('kelas')=='VIII' ? 'selected' : '' }}>VIII</option>
                        <option value="IX" {{ old('kelas')=='IX' ? 'selected' : '' }}>IX</option>
                        <option value="X" {{ old('kelas')=='X' ? 'selected' : '' }}>X</option>
                        <option value="XI" {{ old('kelas')=='XI' ? 'selected' : '' }}>XI</option>
                        <option value="XII" {{ old('kelas')=='XII' ? 'selected' : '' }}>XII</option>
                    </select>
                    @error('kelas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Status Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.siswa.index') }}"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection