@extends('layouts.app')

@section('title', 'Detail Operator')

@section('page-title', 'Detail Operator')

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

    <!-- Operator Info -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Informasi Operator</h3>
            <div class="space-x-2">
                <a href="{{ route('superadmin.operator.edit', $operator) }}"
                    class="inline-flex items-center bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition-colors text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <table class="w-full">
                    <tr class="border-b">
                        <td class="py-3 text-gray-600 font-medium w-1/3">Nama</td>
                        <td class="py-3 text-gray-900">{{ $operator->name }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-3 text-gray-600 font-medium">Email</td>
                        <td class="py-3 text-gray-900">{{ $operator->email }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="w-full">
                    <tr class="border-b">
                        <td class="py-3 text-gray-600 font-medium w-1/3">No. HP</td>
                        <td class="py-3 text-gray-900">{{ $operator->no_hp ?? '-' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-3 text-gray-600 font-medium">Terdaftar</td>
                        <td class="py-3 text-gray-900">{{ $operator->created_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Role Info -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Hak Akses</h3>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800">
                <span class="font-semibold">Role: Operator (Admin)</span>
            </p>
            <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                <li>Mengelola data siswa/santri</li>
                <li>Mengelola data wali murid</li>
                <li>Mengelola tagihan dan pembayaran</li>
                <li>Mengkonfirmasi pembayaran</li>
                <li>Mengelola biaya dan rekening sekolah</li>
                <li>Mengakses pengaturan sistem</li>
            </ul>
        </div>
    </div>
</div>
@endsection