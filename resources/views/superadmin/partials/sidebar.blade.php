<!-- Dashboard -->
<a href="{{ route('superadmin.dashboard') }}"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('superadmin.dashboard') ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span class="font-medium">Dashboard</span>
</a>

<!-- Data Santri -->
<a href="{{ route('superadmin.siswa.index') }}"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('superadmin.siswa.*') ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <span class="font-medium">Data Santri</span>
</a>

<!-- Data Wali Santri -->
<a href="{{ route('superadmin.wali-murid.index') }}"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('superadmin.wali-murid.*') ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
    <span class="font-medium">Data Wali Santri</span>
</a>

<!-- Divider -->
<div class="my-4 border-t border-green-700"></div>
<p class="px-4 text-xs font-semibold text-green-300 uppercase tracking-wider mb-2">Laporan</p>

<!-- Rekap Pembayaran -->
<a href="{{ route('superadmin.laporan.rekap-pembayaran') }}"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('superadmin.laporan.rekap-pembayaran') ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
    </svg>
    <span class="font-medium">Rekap Pembayaran</span>
</a>

<!-- Laporan Pembayaran -->
<a href="{{ route('superadmin.laporan.pembayaran') }}"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('superadmin.laporan.pembayaran') ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
    <span class="font-medium">Laporan Pembayaran</span>
</a>

<!-- Laporan Tagihan -->
<a href="{{ route('superadmin.laporan.tagihan') }}"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('superadmin.laporan.tagihan') ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
    </svg>
    <span class="font-medium">Laporan Tagihan</span>
</a>

<!-- Divider -->
<div class="my-4 border-t border-green-700"></div>
<p class="px-4 text-xs font-semibold text-green-300 uppercase tracking-wider mb-2">Kelola Pengguna</p>

<!-- Data Operator -->
<a href="{{ route('superadmin.operator.index') }}"
    class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('superadmin.operator.*') ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    <span class="font-medium">Data Operator</span>
</a>