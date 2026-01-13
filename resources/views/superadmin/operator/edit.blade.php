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
    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Form Edit Operator</h3>

        <form method="POST" action="{{ route('superadmin.operator.update', $operator) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $operator->name) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $operator->email) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $operator->no_hp) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 @error('no_hp') border-red-500 @enderror">
                    @error('no_hp')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div></div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" id="password"
                        class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                        Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('superadmin.operator.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection