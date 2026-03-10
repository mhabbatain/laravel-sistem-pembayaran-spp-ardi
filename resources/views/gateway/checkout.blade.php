<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Gateway - Checkout</title>
    @vite(['resources/css/app.css'])
    <style>
        body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); min-height: 100vh; }
        .gateway-card { background: rgba(255,255,255,0.97); border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); }
        .countdown-ring { animation: pulse-ring 2s ease-in-out infinite; }
        @keyframes pulse-ring { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .shimmer { background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%); background-size: 200% 100%; animation: shimmer 1.5s infinite; }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    
    <div class="w-full max-w-lg">
        {{-- Header Gateway --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 mb-3">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="text-white/80 text-sm font-medium">Secure Payment Gateway</span>
            </div>
            <h1 class="text-white text-2xl font-bold">SPP Payment Gateway</h1>
            <p class="text-slate-400 text-sm mt-1">Simulasi Payment Gateway</p>
        </div>

        {{-- Main Card --}}
        <div class="gateway-card overflow-hidden">
            {{-- Status Bar --}}
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-white rounded-full countdown-ring"></div>
                    <span class="text-white text-sm font-medium">Menunggu Pembayaran</span>
                </div>
                <div class="flex items-center space-x-1 text-white/90 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="countdown">--:--</span>
                </div>
            </div>

            {{-- Transaction Info --}}
            <div class="p-6">
                {{-- Merchant Info --}}
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Merchant</p>
                        <p class="font-semibold text-gray-800">{{ $transaksi->gateway_response['merchant'] ?? 'Pondok Pesantren' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Kode Transaksi</p>
                        <p class="font-mono font-semibold text-gray-800">{{ $transaksi->kode_transaksi }}</p>
                    </div>
                </div>

                {{-- Amount --}}
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                    <p class="text-4xl font-bold text-gray-900">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                </div>

                {{-- Payment Details --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Siswa</span>
                        <span class="font-medium text-gray-800">{{ $transaksi->pembayaran->tagihan->siswa->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Periode</span>
                        <span class="font-medium text-gray-800">{{ $transaksi->pembayaran->tagihan->bulan ?? '-' }}/{{ $transaksi->pembayaran->tagihan->tahun ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Metode Pembayaran</span>
                        <span class="font-medium text-gray-800">{{ $transaksi->metodePembayaran->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Kategori</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            {{ str_replace('_', ' ', ucfirst($transaksi->metodePembayaran->kategori ?? '')) }}
                        </span>
                    </div>
                </div>

                {{-- Gateway Steps Indicator --}}
                <div class="mb-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Tahapan Proses</p>
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-[10px] text-gray-500 mt-1">Inisiasi</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-emerald-500 mx-1"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-[10px] text-gray-500 mt-1">Kirim Data</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-200 mx-1"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-gray-400">3</span>
                            </div>
                            <span class="text-[10px] text-gray-400 mt-1">Otorisasi</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-200 mx-1"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-gray-400">4</span>
                            </div>
                            <span class="text-[10px] text-gray-400 mt-1">Konfirmasi</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-200 mx-1"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-gray-400">5</span>
                            </div>
                            <span class="text-[10px] text-gray-400 mt-1">Settlement</span>
                        </div>
                    </div>
                </div>

                @if($transaksi->status === 'pending' || $transaksi->status === 'processing')
                {{-- Pay Button --}}
                <form action="{{ route('gateway.process', $transaksi->token) }}" method="POST">
                    @csrf
                    <button type="submit" id="pay-btn"
                        class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold text-lg rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-emerald-500/25">
                        Bayar Sekarang
                    </button>
                </form>
                @elseif($transaksi->status === 'expired')
                <div class="text-center py-4">
                    <div class="inline-flex items-center space-x-2 text-red-600 bg-red-50 rounded-lg px-4 py-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Transaksi telah kadaluarsa</span>
                    </div>
                </div>
                @endif

                {{-- Security Info --}}
                <div class="mt-4 flex items-center justify-center space-x-4 text-xs text-gray-400">
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>SSL Encrypted</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>PCI DSS Compliant</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                        </svg>
                        <span>Tokenized</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6">
            <p class="text-slate-500 text-xs">Powered by <span class="text-emerald-400 font-semibold">SPP Gateway</span> • Simulasi Demo</p>
        </div>
    </div>

    <script>
        // Countdown timer
        const expiredAt = new Date('{{ $transaksi->expired_at->toIso8601String() }}');
        const countdownEl = document.getElementById('countdown');
        
        function updateCountdown() {
            const now = new Date();
            const diff = expiredAt - now;
            
            if (diff <= 0) {
                countdownEl.textContent = '00:00';
                countdownEl.parentElement.classList.add('text-red-200');
                const payBtn = document.getElementById('pay-btn');
                if (payBtn) {
                    payBtn.disabled = true;
                    payBtn.textContent = 'Waktu Habis';
                    payBtn.classList.remove('from-emerald-500', 'to-teal-600');
                    payBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                }
                return;
            }
            
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            countdownEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (diff < 60000) {
                countdownEl.parentElement.classList.add('text-red-200');
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>
