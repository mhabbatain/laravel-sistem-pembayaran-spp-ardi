@extends('layouts.app')

@section('title', 'Pengaturan')

@section('page-title', 'Pengaturan')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'pengaturan'])
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Pengaturan Aplikasi</h2>

        <form action="{{ route('admin.pengaturan.update') }}" method="POST" class="space-y-6">
            @csrf

            @foreach ($pengaturan as $item)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ ucwords(str_replace('_', ' ', $item->key)) }}
                </label>
                @if ($item->key === 'pesan_whatsapp')
                <textarea name="{{ $item->key }}" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ $item->value }}</textarea>
                @else
                <input type="text" name="{{ $item->key }}" value="{{ $item->value }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                @endif
                @if ($item->description)
                <p class="text-xs text-gray-500 mt-1">{{ $item->description }}</p>
                @endif
            </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection