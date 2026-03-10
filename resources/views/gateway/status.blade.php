<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Status Pembayaran - {{ $transaksi->kode_transaksi }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body { 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); 
            min-height: 100vh; 
        }
        .status-card { 
            background: rgba(255,255,255,0.97); 
            border-radius: 16px; 
            box-shadow: 0 25px 50px rgba(0,0,0,0.3); 
        }
        @keyframes check-in { 
            from { transform: scale(0) rotate(-45deg); opacity: 0; } 
            to { transform: scale(1) rotate(0); opacity: 1; } 
        }
        .check-animate { animation: check-in 0.4s ease-out forwards; }
        @keyframes pulse-dot { 
            0%, 100% { transform: scale(1); opacity: 1; } 
            50% { transform: scale(1.3); opacity: 0.7; } 
        }
        .pulse-dot { animation: pulse-dot 1.5s ease-in-out infinite; }
        @keyframes slide-down { 
            from { opacity: 0; transform: translateY(-10px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        .slide-down { animation: slide-down 0.5s ease-out forwards; }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    
    <div class="w-full max-w-lg">
        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 mb-3">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="text-white/80 text-sm font-medium">SPP Payment Gateway</span>
            </div>
            <h1 class="text-white text-2xl font-bold">Status Pembayaran</h1>
        </div>

        {{-- Status Card --}}
        <div class="status-card overflow-hidden">
            {{-- Status Banner --}}
            @php
                $bgColor = match($transaksi->status) {
                    'settled' => 'from-emerald-500 to-green-600',
                    'failed' => 'from-red-500 to-red-600',
                    'expired' => 'from-gray-500 to-gray-600',
                    default => 'from-blue-500 to-blue-600',
                };
                $icon = match($transaksi->status) {
                    'settled' => 'M5 13l4 4L19 7',
                    'failed' => 'M6 18L18 6M6 6l12 12',
                    'expired' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    default => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                };
            @endphp
            <div class="bg-gradient-to-r {{ $bgColor }} px-6 py-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-3 check-animate">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $icon }}"/>
                    </svg>
                </div>
                <h2 class="text-white text-xl font-bold">{{ $transaksi->status_label }}</h2>
                <p class="text-white/80 text-sm mt-1">{{ $transaksi->kode_transaksi }}</p>
            </div>

            <div class="p-6">
                {{-- Amount --}}
                <div class="text-center mb-6 pb-4 border-b border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Total Dibayarkan</p>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                </div>

                {{-- 5-Step Timeline --}}
                <div class="mb-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-4 font-medium">Alur Pembayaran (5 Tahap)</p>
                    
                    @php
                        $response = $transaksi->gateway_response ?? [];
                        $stage = $response['stage'] ?? 'pending';
                        
                        $steps = [
                            ['key' => 'initiated', 'label' => 'Inisiasi Pembayaran', 'desc' => 'Customer memilih metode pembayaran', 'time_key' => 'initiated_at'],
                            ['key' => 'data_sent', 'label' => 'Pengiriman Data Transaksi', 'desc' => 'Data transaksi dikirim terenkripsi ke gateway', 'time_key' => 'data_sent_at'],
                            ['key' => 'authorized', 'label' => 'Proses Otorisasi', 'desc' => 'Bank memverifikasi ketersediaan dana', 'time_key' => 'authorized_at'],
                            ['key' => 'confirmed', 'label' => 'Konfirmasi Status', 'desc' => 'Gateway mengirim konfirmasi ke merchant', 'time_key' => 'confirmed_at'],
                            ['key' => 'settled', 'label' => 'Settlement', 'desc' => 'Dana dicairkan ke rekening merchant', 'time_key' => 'settled_at'],
                        ];

                        $stageOrder = ['pending' => 0, 'initiated' => 1, 'data_sent' => 1, 'processing' => 2, 'authorization' => 2, 'authorized' => 3, 'confirmed' => 4, 'settled' => 5, 'failed' => -1, 'expired' => -2];
                        $currentOrder = $stageOrder[$stage] ?? 0;
                        $isFailed = in_array($stage, ['failed', 'expired']);
                    @endphp

                    <div class="space-y-0">
                        @foreach($steps as $index => $step)
                            @php
                                $stepNum = $index + 1;
                                $isCompleted = !$isFailed && $currentOrder >= $stepNum;
                                $isCurrent = !$isFailed && $currentOrder == $stepNum - 1;
                                $isFailedStep = $isFailed && $stepNum === 3; // Otorisasi is where failure happens
                                $timeValue = $response[$step['time_key']] ?? null;
                            @endphp
                            <div class="flex items-start slide-down" style="animation-delay: {{ $index * 0.1 }}s">
                                {{-- Timeline Dot & Line --}}
                                <div class="flex flex-col items-center mr-4">
                                    @if($isCompleted)
                                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0 check-animate" style="animation-delay: {{ $index * 0.15 }}s">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    @elseif($isFailedStep)
                                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                    @elseif($isCurrent)
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 pulse-dot">
                                            <div class="w-2 h-2 bg-white rounded-full"></div>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-xs font-bold text-gray-400">{{ $stepNum }}</span>
                                        </div>
                                    @endif
                                    @if($index < count($steps) - 1)
                                        <div class="w-0.5 h-10 {{ $isCompleted ? 'bg-emerald-300' : ($isFailedStep ? 'bg-red-200' : 'bg-gray-200') }}"></div>
                                    @endif
                                </div>

                                {{-- Step Content --}}
                                <div class="pb-6 flex-1">
                                    <p class="font-semibold text-sm {{ $isCompleted ? 'text-gray-800' : ($isFailedStep ? 'text-red-600' : ($isCurrent ? 'text-blue-600' : 'text-gray-400')) }}">
                                        {{ $step['label'] }}
                                    </p>
                                    <p class="text-xs {{ $isCompleted ? 'text-gray-500' : ($isFailedStep ? 'text-red-400' : 'text-gray-400') }}">
                                        @if($isFailedStep)
                                            {{ $transaksi->failure_reason ?? ($stage === 'expired' ? 'Transaksi melewati batas waktu' : 'Otorisasi ditolak oleh bank/provider') }}
                                        @else
                                            {{ $step['desc'] }}
                                        @endif
                                    </p>
                                    @if($timeValue)
                                        <p class="text-[10px] text-gray-400 mt-0.5 font-mono">
                                            {{ \Carbon\Carbon::parse($timeValue)->format('d/m/Y H:i:s') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Transaction Details --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Siswa</span>
                        <span class="font-medium text-gray-800">{{ $transaksi->pembayaran->tagihan->siswa->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Periode</span>
                        <span class="font-medium text-gray-800">{{ $transaksi->pembayaran->tagihan->bulan ?? '-' }}/{{ $transaksi->pembayaran->tagihan->tahun ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-medium text-gray-800">{{ $transaksi->metodePembayaran->nama ?? '-' }}</span>
                    </div>
                    @if($transaksi->status === 'settled')
                    <div class="flex justify-between text-sm border-t border-gray-200 pt-2">
                        <span class="text-gray-500">Settlement Ref</span>
                        <span class="font-mono font-medium text-gray-800">{{ $response['settlement_ref'] ?? '-' }}</span>
                    </div>
                    @endif
                    @if(isset($response['authorization_code']))
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Auth Code</span>
                        <span class="font-mono font-medium text-gray-800">{{ $response['authorization_code'] }}</span>
                    </div>
                    @endif
                </div>

                {{-- Digital Receipt (SVG Preview) --}}
                @if($transaksi->status === 'settled')
                <div class="mb-6 slide-down" style="animation-delay: 0.6s">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-2 font-medium">Digital Receipt</p>
                    <div class="border rounded-xl overflow-hidden bg-gray-100 p-2 shadow-inner">
                        <img src="{{ asset('storage/' . $transaksi->pembayaran->bukti_pembayaran) }}" 
                             alt="Digital Receipt" 
                             class="w-full h-auto rounded-lg shadow-sm border border-gray-200">
                    </div>
                    <div class="mt-2 text-center">
                        <a href="{{ asset('storage/' . $transaksi->pembayaran->bukti_pembayaran) }}" 
                           target="_blank" 
                           class="text-xs text-emerald-600 font-semibold hover:text-emerald-700 underline">
                           Unduh Bukti Pembayaran (SVG)
                        </a>
                    </div>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="space-y-3">
                    @if($transaksi->status === 'settled')
                        <a href="{{ route('wali.tagihan.show', $transaksi->pembayaran->tagihan_id) }}"
                            class="block w-full py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold text-center rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all shadow-lg shadow-green-500/25">
                            ✓ Kembali ke Halaman Tagihan
                        </a>
                    @elseif($transaksi->status === 'failed' || $transaksi->status === 'expired')
                        <a href="{{ route('wali.tagihan.show', $transaksi->pembayaran->tagihan_id) }}"
                            class="block w-full py-3 bg-gray-600 text-white font-bold text-center rounded-xl hover:bg-gray-700 transition-all">
                            Kembali ke Halaman Tagihan
                        </a>
                    @else
                        <div class="text-center py-4">
                            <div class="inline-flex items-center space-x-2 text-blue-600">
                                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span class="text-sm font-medium">Memproses pembayaran...</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6">
            <p class="text-slate-500 text-xs">Powered by <span class="text-emerald-400 font-semibold">SPP Gateway</span> • Simulasi Demo</p>
        </div>
    </div>
</body>
</html>
