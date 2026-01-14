@extends('layouts.app')

@section('title', 'Edit Operator')

@section('page-title', 'Edit Operator')

@section('sidebar-menu')
@include('superadmin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('superadmin.operator.index') }}"
            class="inline-flex items-center text-green-600 hover:text-green-800">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Data Operator
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Form Edit Operator</h3>

        <form method="POST" action="{{ route('superadmin.operator.update', $operator) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $operator->name) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @else border-gray-300 @enderror"
                        required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $operator->email) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @else border-gray-300 @enderror"
                        required>
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $operator->no_hp) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 @error('no_hp') border-red-500 @else border-gray-300 @enderror">
                    @error('no_hp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @else border-gray-300 @enderror">
                    <p class="text-gray-500 text-xs mt-1">Kosongkan jika tidak ingin mengubah password</p>
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi
                        Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('superadmin.operator.index') }}"
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