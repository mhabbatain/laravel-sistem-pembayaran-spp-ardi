<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'SPP Pondok Pesantren Darul Jalal') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .watermark {
            position: fixed;
            top: 50%;
            left: 55%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background-image: url('{{ asset(' yayasan-darul-jalal.jpg') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12;
            pointer-events: none;
            z-index: 1;
        }
    </style>
</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    <!-- Watermark -->
    <div class="watermark"></div>
    <div class="min-h-screen flex relative z-10">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-green-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-6 bg-green-800">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('yayasan-darul-jalal.jpg') }}" alt="Logo" class="w-10 h-10 object-cover">
                    <h1 class="text-white text-lg font-bold">Pondok Pesantren Darul Jalal</h1>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="px-4 py-4 overflow-y-auto h-[calc(100vh-4rem)]">
                @yield('sidebar-menu')
            </nav>
        </div>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 lg:hidden z-40" style="display: none;">
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="hidden lg:block">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title')</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                    <div
                        class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>