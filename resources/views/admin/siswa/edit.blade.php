@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Siswa</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="wali_murid_id" class="block text-sm font-medium text-gray-700 mb-2">Wali Murid</label>
                    <select name="wali_murid_id" id="wali_murid_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('wali_murid_id') border-red-500 @enderror">
                        <option value="">Pilih Wali Murid</option>
                        @foreach($waliMurid as $wali)
                        <option value="{{ $wali->id }}" {{ old('wali_murid_id', $siswa->wali_murid_id) == $wali->id ? 'selected' : '' }}>
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
                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $siswa->nisn) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('nisn') border-red-500 @enderror">
                    @error('nisn')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $siswa->nama) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror">
                    @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <select name="jurusan" id="jurusan" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('jurusan') border-red-500 @enderror">
                        <option value="">Pilih Jurusan</option>
                        <option value="IPA" {{ old('jurusan', $siswa->jurusan) == 'IPA' ? 'selected' : '' }}>IPA</option>
                        <option value="IPS" {{ old('jurusan', $siswa->jurusan) == 'IPS' ? 'selected' : '' }}>IPS</option>
                    </select>
                    @error('jurusan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-2">Angkatan</label>
                    <input type="text" name="angkatan" id="angkatan" value="{{ old('angkatan', $siswa->angkatan) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('angkatan') border-red-500 @enderror">
                    @error('angkatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas" id="kelas" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('kelas') border-red-500 @enderror">
                        <option value="">Pilih Kelas</option>
                        <option value="VII" {{ old('kelas', $siswa->kelas) == 'VII' ? 'selected' : '' }}>VII</option>
                        <option value="VIII" {{ old('kelas', $siswa->kelas) == 'VIII' ? 'selected' : '' }}>VIII</option>
                        <option value="IX" {{ old('kelas', $siswa->kelas) == 'IX' ? 'selected' : '' }}>IX</option>
                        <option value="X" {{ old('kelas', $siswa->kelas) == 'X' ? 'selected' : '' }}>X</option>
                        <option value="XI" {{ old('kelas', $siswa->kelas) == 'XI' ? 'selected' : '' }}>XI</option>
                        <option value="XII" {{ old('kelas', $siswa->kelas) == 'XII' ? 'selected' : '' }}>XII</option>
                    </select>
                    @error('kelas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $siswa->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection