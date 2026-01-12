@extends('layouts.app')

@section('title', 'Pengaturan WhatsApp')

@section('page-title', 'Pengaturan WhatsApp')

@section('sidebar-menu')
@include('admin.partials.sidebar-menu', ['active' => 'whatsapp'])
@endsection

@section('content')
<div class="space-y-6">
    <!-- Status Connection Card -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Status Koneksi WhatsApp</h2>
                <p class="text-sm text-gray-600 mt-1">Hubungkan WhatsApp untuk mengirim notifikasi otomatis</p>
            </div>
            <div id="connection-status" class="flex items-center space-x-2">
                @if($connectionStatus['connected'] ?? false)
                <span class="flex h-3 w-3 relative">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-green-600 font-medium">Terhubung</span>
                @else
                <span class="flex h-3 w-3 relative">
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                <span class="text-red-600 font-medium">Tidak Terhubung</span>
                @endif
            </div>
        </div>

        <!-- QR Code Section -->
        <div id="qr-section"
            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center {{ ($connectionStatus['connected'] ?? false) ? 'hidden' : '' }}">
            <div id="qr-container" class="hidden">
                <p class="text-gray-600 mb-4">Scan QR Code dengan WhatsApp di HP Anda</p>
                <div class="flex justify-center mb-4">
                    <img id="qr-image" src="" alt="QR Code" class="max-w-xs border rounded-lg">
                </div>
                <p class="text-sm text-gray-500">QR Code akan refresh otomatis setiap 30 detik</p>
            </div>

            <div id="qr-loading" class="hidden">
                <svg class="animate-spin h-10 w-10 text-green-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="text-gray-600">Memuat QR Code...</p>
            </div>

            <div id="qr-placeholder">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <p class="text-gray-600 mb-4">Klik tombol di bawah untuk menghubungkan WhatsApp</p>
                <button type="button" onclick="connectWhatsApp()"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Hubungkan WhatsApp
                </button>
            </div>
        </div>

        <!-- Connected Info -->
        <div id="connected-section" class="{{ ($connectionStatus['connected'] ?? false) ? '' : 'hidden' }}">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-green-800">WhatsApp Terhubung</p>
                        <p class="text-sm text-green-600" id="connected-phone">{{ $settings['connected_phone'] ?: 'Nomor
                            tidak tersedia' }}</p>
                    </div>
                </div>
                <button type="button" onclick="disconnectWhatsApp()"
                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                    Putuskan Koneksi
                </button>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 flex space-x-3">
            <button type="button" onclick="checkStatus()"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Status
            </button>
            <button type="button" onclick="restartInstance()"
                class="px-4 py-2 border border-orange-300 text-orange-700 rounded-lg hover:bg-orange-50 transition inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Restart Instance
            </button>
        </div>
    </div>

    <!-- Settings Card -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Pengaturan Notifikasi</h2>

        <form action="{{ route('admin.whatsapp.settings') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Enable/Disable WhatsApp -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800">Aktifkan Notifikasi WhatsApp</p>
                    <p class="text-sm text-gray-600">Mengaktifkan/menonaktifkan semua notifikasi WhatsApp</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="whatsapp_enabled" value="0">
                    <input type="checkbox" name="whatsapp_enabled" value="1" class="sr-only peer" {{
                        $settings['enabled']=='1' ? 'checked' : '' }}>
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                    </div>
                </label>
            </div>

            <!-- Notification Types -->
            <div class="space-y-4">
                <p class="font-medium text-gray-800">Jenis Notifikasi</p>

                <!-- Tagihan Notification -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-700">Notifikasi Tagihan Baru</p>
                        <p class="text-sm text-gray-500">Kirim notifikasi saat tagihan baru dibuat</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="whatsapp_notify_tagihan" value="0">
                        <input type="checkbox" name="whatsapp_notify_tagihan" value="1" class="sr-only peer" {{
                            $settings['notify_tagihan']=='1' ? 'checked' : '' }}>
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                        </div>
                    </label>
                </div>

                <!-- Pembayaran Notification -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-700">Notifikasi Konfirmasi Pembayaran</p>
                        <p class="text-sm text-gray-500">Kirim notifikasi saat pembayaran dikonfirmasi</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="whatsapp_notify_pembayaran" value="0">
                        <input type="checkbox" name="whatsapp_notify_pembayaran" value="1" class="sr-only peer" {{
                            $settings['notify_pembayaran']=='1' ? 'checked' : '' }}>
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                        </div>
                    </label>
                </div>

                <!-- Reminder Notification -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-700">Notifikasi Pengingat</p>
                        <p class="text-sm text-gray-500">Kirim pengingat untuk tagihan yang belum dibayar</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="whatsapp_notify_reminder" value="0">
                        <input type="checkbox" name="whatsapp_notify_reminder" value="1" class="sr-only peer" {{
                            $settings['notify_reminder']=='1' ? 'checked' : '' }}>
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Test Message Card -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Kirim Pesan Test</h2>

        <div class="space-y-4">
            <div>
                <label for="test_phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor WhatsApp
                </label>
                <input type="text" id="test_phone" placeholder="08123456789"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Masukkan nomor tanpa spasi atau tanda hubung</p>
            </div>

            <div>
                <label for="test_message" class="block text-sm font-medium text-gray-700 mb-2">
                    Pesan
                </label>
                <textarea id="test_message" rows="4" placeholder="Tulis pesan test Anda di sini..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">🔔 *TEST NOTIFIKASI*

Ini adalah pesan test dari Sistem Pembayaran SPP Pondok Pesantren Darul Jalal.

Jika Anda menerima pesan ini, berarti koneksi WhatsApp sudah berfungsi dengan baik.

_Terima kasih_</textarea>
            </div>

            <button type="button" onclick="sendTestMessage()"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                Kirim Pesan Test
            </button>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="font-medium text-blue-800">Informasi Penting</h3>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside space-y-1">
                    <li>Pastikan Evolution API berjalan di <code
                            class="bg-blue-100 px-1 rounded">{{ config('services.whatsapp.api_url') }}</code></li>
                    <li>Gunakan nomor WhatsApp yang tidak digunakan untuk WhatsApp Web di tempat lain</li>
                    <li>Scan QR Code menggunakan WhatsApp di HP Anda (Menu > Linked Devices)</li>
                    <li>Jangan logout dari WhatsApp di HP untuk menjaga koneksi tetap aktif</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let qrRefreshInterval = null;
let statusCheckInterval = null;

function connectWhatsApp() {
    // First, create instance if not exists
    createInstance().then(() => {
        // Then get QR code
        getQrCode();
    });
}

async function createInstance() {
    try {
        const response = await fetch('{{ route('admin.whatsapp.create-instance') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const data = await response.json();
        console.log('Instance created:', data);
    } catch (error) {
        console.error('Error creating instance:', error);
    }
}

async function getQrCode() {
    document.getElementById('qr-placeholder').classList.add('hidden');
    document.getElementById('qr-loading').classList.remove('hidden');
    
    try {
        const response = await fetch('{{ route('admin.whatsapp.qr-code') }}');
        const data = await response.json();
        
        document.getElementById('qr-loading').classList.add('hidden');
        
        if (data.success && data.qrcode) {
            document.getElementById('qr-container').classList.remove('hidden');
            document.getElementById('qr-image').src = data.qrcode.startsWith('data:') 
                ? data.qrcode 
                : 'data:image/png;base64,' + data.qrcode;
            
            // Start checking connection status
            startStatusCheck();
            
            // Refresh QR code every 30 seconds
            qrRefreshInterval = setInterval(getQrCode, 30000);
        } else {
            document.getElementById('qr-placeholder').classList.remove('hidden');
            alert(data.message || 'Gagal mendapatkan QR Code');
        }
    } catch (error) {
        document.getElementById('qr-loading').classList.add('hidden');
        document.getElementById('qr-placeholder').classList.remove('hidden');
        alert('Tidak dapat terhubung ke server WhatsApp. Pastikan Evolution API sudah berjalan.');
    }
}

function startStatusCheck() {
    statusCheckInterval = setInterval(checkStatus, 5000);
}

async function checkStatus() {
    try {
        const response = await fetch('{{ route('admin.whatsapp.status') }}');
        const data = await response.json();
        
        updateConnectionUI(data.connected, data.phoneNumber, data.profileName);
        
        if (data.connected) {
            // Stop intervals
            if (qrRefreshInterval) clearInterval(qrRefreshInterval);
            if (statusCheckInterval) clearInterval(statusCheckInterval);
        }
    } catch (error) {
        console.error('Error checking status:', error);
    }
}

function updateConnectionUI(connected, phoneNumber = null, profileName = null) {
    const statusDiv = document.getElementById('connection-status');
    const qrSection = document.getElementById('qr-section');
    const connectedSection = document.getElementById('connected-section');
    const connectedPhoneEl = document.getElementById('connected-phone');
    
    if (connected) {
        statusDiv.innerHTML = `
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span class="text-green-600 font-medium">Terhubung</span>
        `;
        qrSection.classList.add('hidden');
        connectedSection.classList.remove('hidden');
        
        // Update phone number display
        if (connectedPhoneEl) {
            let displayText = phoneNumber || 'Memuat nomor...';
            if (profileName) {
                displayText = `${profileName} (${phoneNumber || 'memuat...'})`;
            }
            connectedPhoneEl.textContent = displayText;
        }
    } else {
        statusDiv.innerHTML = `
            <span class="flex h-3 w-3 relative">
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            <span class="text-red-600 font-medium">Tidak Terhubung</span>
        `;
        connectedSection.classList.add('hidden');
        qrSection.classList.remove('hidden');
    }
}

async function disconnectWhatsApp() {
    if (!confirm('Apakah Anda yakin ingin memutuskan koneksi WhatsApp?')) return;
    
    try {
        const response = await fetch('{{ route('admin.whatsapp.disconnect') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const data = await response.json();
        
        if (data.success) {
            updateConnectionUI(false);
            document.getElementById('qr-placeholder').classList.remove('hidden');
            document.getElementById('qr-container').classList.add('hidden');
            alert('WhatsApp berhasil diputuskan');
        } else {
            alert(data.message || 'Gagal memutuskan koneksi');
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

async function restartInstance() {
    if (!confirm('Apakah Anda yakin ingin me-restart instance WhatsApp? Anda perlu scan QR code ulang.')) return;
    
    try {
        const response = await fetch('{{ route('admin.whatsapp.restart') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const data = await response.json();
        
        if (data.success) {
            updateConnectionUI(false);
            document.getElementById('qr-placeholder').classList.remove('hidden');
            document.getElementById('qr-container').classList.add('hidden');
            alert('Instance berhasil di-restart. Silakan hubungkan ulang WhatsApp.');
        } else {
            alert(data.message || 'Gagal me-restart instance');
        }
    } catch (error) {
        alert('Terjadi kesalahan');
    }
}

async function sendTestMessage() {
    const phone = document.getElementById('test_phone').value;
    const message = document.getElementById('test_message').value;
    
    if (!phone || !message) {
        alert('Mohon isi nomor WhatsApp dan pesan');
        return;
    }
    
    try {
        const response = await fetch('{{ route('admin.whatsapp.test') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone, message })
        });
        const data = await response.json();
        
        if (data.success) {
            alert('Pesan test berhasil dikirim!');
        } else {
            alert(data.message || 'Gagal mengirim pesan');
        }
    } catch (error) {
        alert('Terjadi kesalahan saat mengirim pesan');
    }
}

// Cleanup on page leave
window.addEventListener('beforeunload', () => {
    if (qrRefreshInterval) clearInterval(qrRefreshInterval);
    if (statusCheckInterval) clearInterval(statusCheckInterval);
});
</script>
@endpush
@endsection