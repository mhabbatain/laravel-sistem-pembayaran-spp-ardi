<!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'dashboard' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span class="font-medium">Dashboard</span>
</a>

<!-- Data User -->
<a href="{{ route('admin.user.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'users' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <span class="font-medium">Data User</span>
</a>

<!-- Rekening Sekolah -->
<a href="{{ route('admin.rekening-sekolah.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'rekening-sekolah' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
    </svg>
    <span class="font-medium">Rekening Sekolah</span>
</a>

<!-- Wali Murid -->
<a href="{{ route('admin.wali-murid.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'wali-murid' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
    <span class="font-medium">Wali Murid</span>
</a>

<!-- Data Siswa -->
<a href="{{ route('admin.siswa.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'siswa' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <span class="font-medium">Data Siswa</span>
</a>

<!-- Data Biaya -->
<a href="{{ route('admin.biaya.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'biaya' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <span class="font-medium">Data Biaya</span>
</a>

<!-- Data Tagihan -->
<a href="{{ route('admin.tagihan.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'tagihan' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
    </svg>
    <span class="font-medium">Data Tagihan</span>
</a>

<!-- Data Pembayaran -->
<a href="{{ route('admin.pembayaran.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'pembayaran' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
    <span class="font-medium">Data Pembayaran</span>
</a>

<!-- Buat Tagihan -->
<a href="{{ route('admin.job-tagihan.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'job-tagihan' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    <span class="font-medium">Buat Tagihan</span>
</a>

<!-- Pengaturan -->
<a href="{{ route('admin.pengaturan.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ $active === 'pengaturan' ? 'text-white bg-blue-600' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    <span class="font-medium">Pengaturan</span>
</a>