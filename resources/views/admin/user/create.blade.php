@extends('layouts.app')

@section('title', 'Tambah User')

@section('page-title', 'Tambah User')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'users'])
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah User Baru</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}">
                @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" id="role" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('role') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="wali" {{ old('role') == 'wali' ? 'selected' : '' }}>Wali Murid</option>
                </select>
                @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 {{ $errors->has('no_hp') ? 'border-red-500' : 'border-gray-300' }}">
                @error('no_hp')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.user.index') }}"
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