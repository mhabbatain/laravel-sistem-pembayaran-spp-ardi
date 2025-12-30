<!-- Dashboard -->
<a href="{{ route('wali.dashboard') }}" 
   class="flex items-center space-x-3 px-4 py-3 {{ $active === 'dashboard' ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-700 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span class="font-medium">Dashboard</span>
</a>

<!-- Data Siswa -->
<a href="{{ route('wali.siswa.index') }}" 
   class="flex items-center space-x-3 px-4 py-3 {{ $active === 'siswa' ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-700 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <span class="font-medium">Data Siswa</span>
</a>

<!-- Tagihan -->
<a href="{{ route('wali.tagihan.index') }}" 
   class="flex items-center space-x-3 px-4 py-3 {{ $active === 'tagihan' ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-700 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
    </svg>
    <span class="font-medium">Tagihan</span>
</a>

<!-- Profil -->
<a href="{{ route('wali.profile.index') }}" 
   class="flex items-center space-x-3 px-4 py-3 {{ $active === 'profile' ? 'text-white bg-green-700' : 'text-gray-300 hover:bg-green-700 hover:text-white' }} rounded-lg mb-1">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    <span class="font-medium">Profil</span>
</a>
