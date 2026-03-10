<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bank Simulator - Otorisasi Pembayaran</title>
    @vite(['resources/css/app.css'])
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #1e3a5f 100%); min-height: 100vh; }
        .bank-card { background: white; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); }
        .otp-input { letter-spacing: 8px; font-family: monospace; }
        .loading-overlay { backdrop-filter: blur(4px); }
        @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .spin-slow { animation: spin-slow 2s linear infinite; }
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fade-in 0.5s ease-out; }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    
    <div class="w-full max-w-md fade-in">
        {{-- Bank Header --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 mb-3">
                <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span class="text-white/80 text-sm font-medium">Simulasi {{ str_replace('_', ' ', ucfirst($transaksi->metodePembayaran->kategori ?? 'Bank')) }}</span>
            </div>
            <h1 class="text-white text-xl font-bold">{{ $transaksi->metodePembayaran->nama ?? 'Bank' }}</h1>
            <p class="text-blue-200 text-sm mt-1">Halaman Otorisasi Pembayaran</p>
        </div>

        {{-- Bank Card --}}
        <div class="bank-card overflow-hidden">
            {{-- Blue Header Bar --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-200 text-xs">Konfirmasi Transaksi</p>
                        <p class="text-white font-semibold">Verifikasi Pembayaran</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-6">
                {{-- Transaction Summary --}}
                <div class="bg-blue-50 rounded-xl p-4 mb-6">
                    <p class="text-xs text-blue-600 uppercase tracking-wider mb-2 font-medium">Detail Transaksi</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Merchant</span>
                            <span class="font-medium text-gray-800">{{ $transaksi->gateway_response['merchant'] ?? 'SPP' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">No. Transaksi</span>
                            <span class="font-mono font-medium text-gray-800">{{ $transaksi->kode_transaksi }}</span>
                        </div>
                        <div class="flex justify-between text-sm border-t border-blue-100 pt-2 mt-2">
                            <span class="text-gray-600 font-medium">Jumlah</span>
                            <span class="font-bold text-lg text-gray-900">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Simulated PIN/OTP Input --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        @if($transaksi->metodePembayaran->kategori === 'e_wallet')
                            PIN {{ $transaksi->metodePembayaran->nama }}
                        @else
                            Kode OTP
                        @endif
                    </label>
                    <input type="text" value="123456" readonly
                        class="otp-input w-full px-4 py-3 text-center text-2xl border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    <p class="text-xs text-gray-400 mt-2 text-center">
                        <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Simulasi: PIN/OTP sudah terisi otomatis
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3">
                    {{-- Approve Button --}}
                    <form action="{{ route('gateway.simulate', $transaksi->token) }}" method="POST" id="approve-form">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" id="approve-btn"
                            class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all transform hover:scale-[1.01] active:scale-[0.99] shadow-lg shadow-green-500/25 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Setujui Pembayaran</span>
                        </button>
                    </form>

                    {{-- Reject Button --}}
                    <form action="{{ route('gateway.simulate', $transaksi->token) }}" method="POST" id="reject-form">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" id="reject-btn"
                            class="w-full py-3.5 bg-white border-2 border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-300 transition-all transform hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Tolak Pembayaran</span>
                        </button>
                    </form>
                </div>

                {{-- Warning --}}
                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-start space-x-2">
                    <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-amber-700 font-medium">Halaman Simulasi</p>
                        <p class="text-xs text-amber-600">Ini adalah simulasi halaman otorisasi bank. Pada sistem nyata, halaman ini disediakan oleh bank/provider pembayaran.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-4">
            <p class="text-blue-200/60 text-xs">Simulasi otorisasi • {{ $transaksi->metodePembayaran->nama ?? 'Bank' }}</p>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black/50 loading-overlay flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 text-center max-w-xs mx-4 shadow-2xl">
            <div class="mb-4">
                <svg class="w-12 h-12 text-blue-500 mx-auto spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <p class="text-gray-800 font-semibold" id="loading-text">Memproses pembayaran...</p>
            <p class="text-gray-400 text-sm mt-1">Mohon tunggu, jangan tutup halaman ini</p>
            <div class="mt-4 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-blue-500 h-full rounded-full" style="animation: progress 3s ease-in-out forwards;"></div>
            </div>
        </div>
    </div>

    <style>
        @keyframes progress { from { width: 0%; } to { width: 100%; } }
    </style>

    <script>
        // Show loading overlay when button is clicked
        document.getElementById('approve-form').addEventListener('submit', function(e) {
            document.getElementById('loading-text').textContent = 'Memverifikasi dan memproses pembayaran...';
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('approve-btn').disabled = true;
            document.getElementById('reject-btn').disabled = true;
        });

        document.getElementById('reject-form').addEventListener('submit', function(e) {
            document.getElementById('loading-text').textContent = 'Membatalkan transaksi...';
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('approve-btn').disabled = true;
            document.getElementById('reject-btn').disabled = true;
        });
    </script>
</body>
</html>
