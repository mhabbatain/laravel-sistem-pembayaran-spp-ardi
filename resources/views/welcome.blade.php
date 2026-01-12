<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Pondok Pesantren Darul Jalal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header/Navbar -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo kecil di header -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('yayasan-darul-jalal.jpg') }}" alt="Logo" class="h-12 w-12 object-contain">
                </div>

                <!-- Tombol Masuk & Daftar -->
                @if (Route::has('login'))
                <nav class="flex items-center space-x-3">
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-flex items-center px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-full hover:bg-green-700 transition-colors">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-6 py-2 text-gray-700 text-sm font-medium hover:text-green-600 transition-colors">
                        Masuk
                    </a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-full hover:bg-green-700 transition-colors">
                        Daftar
                    </a>
                    @endif
                    @endauth
                </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="min-h-[calc(100vh-80px)] flex items-center">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center py-16">
                <!-- Kiri: Teks dan Tombol -->
                <div class="space-y-6">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                        Pondok Pesantren<br>
                        <span class="text-green-600">Darul Jalal</span>
                    </h1>
                    <p class="text-lg text-gray-600 max-w-lg">
                        Sistem Informasi Pembayaran SPP untuk memudahkan pengelolaan administrasi keuangan santri.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center px-8 py-3 bg-gray-900 text-white text-sm font-semibold rounded-full hover:bg-gray-800 transition-colors shadow-lg">
                            BUKA DASHBOARD
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-8 py-3 bg-gray-900 text-white text-sm font-semibold rounded-full hover:bg-gray-800 transition-colors shadow-lg">
                            MASUK SEKARANG
                        </a>
                        @endauth
                        <a href="#fitur"
                            class="inline-flex items-center px-8 py-3 border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:border-green-600 hover:text-green-600 transition-colors">
                            LEBIH LANJUT
                        </a>
                    </div>
                </div>

                <!-- Kanan: Logo Besar -->
                <div class="flex justify-center lg:justify-end">
                    <div class="relative">
                        <img src="{{ asset('yayasan-darul-jalal.jpg') }}" alt="Logo Pondok Pesantren Darul Jalal"
                            class="w-72 h-72 md:w-96 md:h-96 object-contain drop-shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Fitur Section -->
    <section id="fitur" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Sistem</h2>
                <p class="text-gray-600">Kemudahan dalam mengelola pembayaran SPP santri</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="bg-gray-50 rounded-2xl p-8 text-center hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Pembayaran Online</h3>
                    <p class="text-gray-600">Bayar SPP dengan mudah melalui transfer bank atau e-wallet</p>
                </div>

                <!-- Fitur 2 -->
                <div class="bg-gray-50 rounded-2xl p-8 text-center hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Laporan Real-time</h3>
                    <p class="text-gray-600">Pantau status pembayaran dan riwayat transaksi secara real-time</p>
                </div>

                <!-- Fitur 3 -->
                <div class="bg-gray-50 rounded-2xl p-8 text-center hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Notifikasi Otomatis</h3>
                    <p class="text-gray-600">Dapatkan pengingat tagihan dan konfirmasi pembayaran via WhatsApp</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-3 mb-4 md:mb-0">
                    <img src="{{ asset('yayasan-darul-jalal.jpg') }}" alt="Logo"
                        class="h-10 w-10 object-contain rounded">
                    <span class="font-semibold">Pondok Pesantren Darul Jalal</span>
                </div>
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Sistem Pembayaran SPP. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>