@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('page-title', 'Konfirmasi Pembayaran')

@section('sidebar-menu')
@include('wali.partials.sidebar-menu', ['active' => 'tagihan'])
@endsection

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Form Konfirmasi Pembayaran</h2>
            <p class="text-sm text-gray-600 mt-1">Lengkapi formulir untuk mengkonfirmasi pembayaran</p>
        </div>

        <!-- Info Tagihan -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-green-900 mb-3">Informasi Tagihan</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-green-700">Siswa</p>
                    <p class="font-medium text-green-900">{{ $tagihan->siswa->nama }}</p>
                </div>
                <div>
                    <p class="text-green-700">Periode</p>
                    <p class="font-medium text-green-900">{{ $tagihan->bulan }}/{{ $tagihan->tahun }}</p>
                </div>
                <div>
                    <p class="text-green-700">Total Tagihan (Komponen Dipilih)</p>
                    <p class="font-medium text-green-900">Rp {{ number_format($tagihan->total_tagihan_terpilih, 0, ',',
                        '.') }}</p>
                </div>
                <div>
                    <p class="text-green-700">Sisa Tagihan (Komponen Dipilih)</p>
                    <p class="font-medium text-red-600 text-lg">Rp {{ number_format($tagihan->sisa_tagihan_terpilih, 0,
                        ',', '.') }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('wali.pembayaran.store-konfirmasi', $tagihan) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Jumlah Bayar (Hidden, calculated automatically) -->
            <input type="hidden" name="jumlah_bayar" id="jumlah_bayar" value="0">
            <input type="hidden" name="metode_pembayaran_id" id="metode_pembayaran_id" value="">
            <input type="hidden" name="is_ewallet" id="is_ewallet" value="0">

            <!-- Detail Biaya -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 mb-3">Pilih Komponen Pembayaran</h3>
                <p class="text-sm text-gray-600 mb-3">Centang komponen yang ingin Anda bayar</p>
                @error('detail_ids')
                <p class="text-sm text-red-500 mb-2">{{ $message }}</p>
                @enderror
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pilih</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis Biaya
                                </th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Terbayar
                                </th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tagihan->detailTagihan as $detail)
                            @php
                            $sisa = $detail->jumlah - $detail->jumlah_dibayar;
                            @endphp
                            <tr class="komponen-row" data-jumlah="{{ $sisa }}" data-detail-id="{{ $detail->id }}">
                                <td class="px-4 py-2">
                                    @if($sisa > 0)
                                    <input type="checkbox" name="detail_ids[]" value="{{ $detail->id }}"
                                        class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 komponen-checkbox"
                                        {{ old('detail_ids') ? (in_array($detail->id, old('detail_ids')) ? 'checked' :
                                    '') : ($detail->biaya->is_default ? 'checked' : '') }}>
                                    @else
                                    <span class="text-green-600 font-semibold">✓ Lunas</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $detail->biaya->nama_biaya }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right">Rp {{
                                    number_format($detail->jumlah, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-green-600 text-right">Rp {{
                                    number_format($detail->jumlah_dibayar, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right font-semibold">
                                    @if($sisa > 0)
                                    <span class="text-red-600">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                                    @else
                                    <span class="text-green-600">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total
                                    yang akan dibayar:</td>
                                <td class="px-4 py-3 text-right text-base font-bold text-green-600" id="total-terpilih">
                                    Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Pilih Metode Pembayaran -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Metode Pembayaran <span class="text-red-500">*</span>
                </label>
                @error('metode_pembayaran_id')
                <p class="text-sm text-red-500 mb-2">{{ $message }}</p>
                @enderror

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <!-- Bank Transfer -->
                    @if(isset($metodePembayaran['bank_transfer']) && $metodePembayaran['bank_transfer']->count() > 0)
                    <div class="p-4 border-b border-gray-200">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Bank Transfer</p>
                        <div class="flex flex-wrap gap-4">
                            @foreach($metodePembayaran['bank_transfer'] as $metode)
                            <button type="button"
                                onclick="selectMetode({{ $metode->id }}, '{{ $metode->nama }}', '{{ $metode->nomor_rekening }}', '{{ $metode->nama_pemilik }}', '{{ $metode->kategori }}')"
                                class="metode-btn rounded hover:opacity-70 transition-opacity focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 border-gray-200 border"
                                data-metode-id="{{ $metode->id }}">
                                @if($metode->logo)
                                <img src="{{ asset('pembayaran/' . $metode->logo) }}" alt="{{ $metode->nama }}"
                                    class="h-12 border-gray-300 w-auto object-contain">
                                @else
                                <span class="text-sm font-medium text-gray-700 px-2">{{ $metode->nama }}</span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- E-Wallet -->
                    @if(isset($metodePembayaran['e_wallet']) && $metodePembayaran['e_wallet']->count() > 0)
                    <div class="p-4 border-b border-gray-200">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">E-Wallet</p>
                        <div class="flex flex-wrap gap-4">
                            @foreach($metodePembayaran['e_wallet'] as $metode)
                            <button type="button"
                                onclick="selectMetode({{ $metode->id }}, '{{ $metode->nama }}', '{{ $metode->nomor_rekening }}', '{{ $metode->nama_pemilik }}', '{{ $metode->kategori }}')"
                                class="metode-btn rounded hover:opacity-70 transition-opacity focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 border-gray-200 border"
                                data-metode-id="{{ $metode->id }}">
                                @if($metode->logo)
                                <img src="{{ asset('pembayaran/' . $metode->logo) }}" alt="{{ $metode->nama }}"
                                    class="h-12 w-auto object-contain">
                                @else
                                <span class="text-sm font-medium text-gray-700 px-2">{{ $metode->nama }}</span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Kartu Kredit/Debit -->
                    @if(isset($metodePembayaran['kartu']) && $metodePembayaran['kartu']->count() > 0)
                    <div class="p-4 border-b border-gray-200">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Kartu Kredit/Debit
                        </p>
                        <div class="flex flex-wrap gap-4">
                            @foreach($metodePembayaran['kartu'] as $metode)
                            <button type="button"
                                onclick="selectMetode({{ $metode->id }}, '{{ $metode->nama }}', '{{ $metode->nomor_rekening }}', '{{ $metode->nama_pemilik }}', '{{ $metode->kategori }}')"
                                class="metode-btn p-1 rounded hover:opacity-70 transition-opacity focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                data-metode-id="{{ $metode->id }}">
                                @if($metode->logo)
                                <img src="{{ asset('pembayaran/' . $metode->logo) }}" alt="{{ $metode->nama }}"
                                    class="h-8 w-auto object-contain">
                                @else
                                <span class="text-sm font-medium text-gray-700 px-2">{{ $metode->nama }}</span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- QRIS -->
                    @if(isset($metodePembayaran['qris']) && $metodePembayaran['qris']->count() > 0)
                    <div class="p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">QRIS</p>
                        <div class="flex flex-wrap gap-4">
                            @foreach($metodePembayaran['qris'] as $metode)
                            <button type="button"
                                onclick="selectMetode({{ $metode->id }}, '{{ $metode->nama }}', '{{ $metode->nomor_rekening }}', '{{ $metode->nama_pemilik }}', '{{ $metode->kategori }}')"
                                class="metode-btn p-1 rounded hover:opacity-70 transition-opacity focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                data-metode-id="{{ $metode->id }}">
                                @if($metode->logo)
                                <img src="{{ asset('pembayaran/' . $metode->logo) }}" alt="{{ $metode->nama }}"
                                    class="h-8 w-auto object-contain">
                                @else
                                <span class="text-sm font-medium text-gray-700 px-2">{{ $metode->nama }}</span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(!isset($metodePembayaran) || $metodePembayaran->flatten()->count() == 0)
                    <div class="text-center py-6 text-gray-500">
                        <p>Belum ada metode pembayaran tersedia.</p>
                        <p class="text-sm">Silakan hubungi admin.</p>
                    </div>
                    @endif
                </div>

                <!-- Selected Payment Method Info -->
                <div id="selected-metode-info" class="mt-3 p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-800" id="selected-metode-name">-</p>
                                <p class="text-sm text-green-700">
                                    <span id="selected-metode-rekening" class="font-mono">-</span>
                                    <span class="text-green-600">a.n.</span>
                                    <span id="selected-metode-pemilik">-</span>
                                </p>
                            </div>
                        </div>
                        <button type="button" onclick="clearMetode()" class="text-green-600 hover:text-green-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Rekening Tujuan (Legacy - Hidden if metode pembayaran selected) -->
            <div id="rekening-tujuan-section">
                <label for="rekening_tujuan_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Rekening Tujuan <span class="text-red-500">*</span>
                </label>
                <select name="rekening_tujuan_id" id="rekening_tujuan_id" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('rekening_tujuan_id') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Rekening</option>
                    @foreach($rekeningSekolah as $rekening)
                    <option value="{{ $rekening->id }}" {{ old('rekening_tujuan_id')==$rekening->id ? 'selected' : ''
                        }}>
                        {{ $rekening->nama_bank }} - {{ $rekening->nomor_rekening }} ({{ $rekening->pemilik_rekening }})
                    </option>
                    @endforeach
                </select>
                @error('rekening_tujuan_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Pembayaran -->
            <div>
                <label for="tanggal_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Pembayaran <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran"
                    value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('tanggal_pembayaran') ? 'border-red-500' : 'border-gray-300' }}">
                @error('tanggal_pembayaran')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bukti Transfer -->
            <div>
                <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Transfer <span class="text-red-500">*</span>
                </label>
                <div id="upload-section">
                    <input type="file" name="bukti_transfer" id="bukti_transfer" accept="image/*" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('bukti_transfer') ? 'border-red-500' : 'border-gray-300' }}">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
                </div>
                
                <!-- No text notice here anymore -->
                
                <!-- Image Preview Container -->
                <div id="preview-container" class="mt-4 hidden">
                    <p class="text-sm font-medium text-gray-700 mb-2">Preview Bukti:</p>
                    <div class="relative inline-block border rounded-lg overflow-hidden bg-gray-50">
                        <img id="image-preview" src="#" alt="Preview" class="max-w-full h-auto max-h-64">
                        <button type="button" id="remove-preview" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                @error('bukti_transfer')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box: Manual Transfer -->
            <div id="info-manual" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Pastikan Anda sudah melakukan transfer sesuai jumlah yang diinputkan</li>
                                <li>Upload bukti transfer yang jelas dan terbaca</li>
                                <li>Pembayaran akan diverifikasi oleh admin dalam 1x24 jam</li>
                                <li>Anda akan mendapat notifikasi setelah pembayaran dikonfirmasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box: Payment Gateway -->
            <div id="info-gateway" class="bg-blue-50 border border-blue-200 rounded-lg p-4 hidden">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Pembayaran via Payment Gateway</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Anda akan diarahkan ke halaman <strong>Payment Gateway</strong> untuk menyelesaikan pembayaran</li>
                                <li>Proses melewati 5 tahap: Inisiasi → Pengiriman Data → Otorisasi → Konfirmasi → Settlement</li>
                                <li>Pembayaran otomatis dikonfirmasi setelah berhasil melewati semua tahap</li>
                                <li>Tidak perlu upload bukti transfer</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('wali.tagihan.show', $tagihan) }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" id="submit-btn"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="btn-text-manual">Kirim Konfirmasi</span>
                    <span id="btn-text-gateway" class="hidden">🔒 Bayar via Payment Gateway</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.komponen-checkbox');
    const totalElement = document.getElementById('total-terpilih');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');
    const submitBtn = document.getElementById('submit-btn');
    
    function updateTotal() {
        let total = 0;
        let checkedCount = 0;
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkedCount++;
                const row = checkbox.closest('.komponen-row');
                const jumlah = parseFloat(row.dataset.jumlah);
                total += jumlah;
            }
        });
        
        totalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
        jumlahBayarInput.value = total;
        
        // Disable submit button if no checkbox is checked or total is 0
        submitBtn.disabled = checkedCount === 0 || total === 0;
        
        console.log('Checked:', checkedCount, 'Total:', total, 'Disabled:', submitBtn.disabled);
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotal);
    });
    
    // Initial calculation
    updateTotal();

    // Image Preview Logic
    const buktiInput = document.getElementById('bukti_transfer');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removePreviewBtn = document.getElementById('remove-preview');

    buktiInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    removePreviewBtn.addEventListener('click', function() {
        buktiInput.value = '';
        previewContainer.classList.add('hidden');
        imagePreview.src = '#';
    });

    // Handle form submit to show auto proof for e-wallet
    const paymentForm = document.querySelector('form[action*="store-konfirmasi"]');
    paymentForm.addEventListener('submit', function(e) {
        const isEwallet = document.getElementById('is_ewallet').value === '1';
        if (isEwallet && previewContainer.classList.contains('hidden')) {
            e.preventDefault();
            
            // Show preview
            previewContainer.classList.remove('hidden');
            
            // Add a temporary loading state to the button
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
            
            // Small delay to let user see the "proof" before it submits
            setTimeout(() => {
                paymentForm.submit();
            }, 800);
        }
    });
});

// Metode Pembayaran functions
function selectMetode(id, nama, rekening, pemilik, kategori) {
    // Update hidden inputs
    document.getElementById('metode_pembayaran_id').value = id;
    const isGateway = ['e_wallet', 'qris', 'kartu'].includes(kategori);
    document.getElementById('is_ewallet').value = isGateway ? '1' : '0';
    
    // UI elements
    const uploadSection = document.getElementById('upload-section');
    const buktiInput = document.getElementById('bukti_transfer');
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removePreviewBtn = document.getElementById('remove-preview');
    const rekeningSection = document.getElementById('rekening-tujuan-section');
    const rekeningSelect = document.getElementById('rekening_tujuan_id');
    const infoManual = document.getElementById('info-manual');
    const infoGateway = document.getElementById('info-gateway');
    const btnManual = document.getElementById('btn-text-manual');
    const btnGateway = document.getElementById('btn-text-gateway');
    
    if (isGateway) {
        // Gateway payment: hide upload & rekening, show gateway info
        uploadSection.classList.add('hidden');
        buktiInput.required = false;
        previewContainer.classList.add('hidden');
        
        rekeningSection.classList.add('hidden');
        rekeningSelect.required = false;
        rekeningSelect.value = '';
        
        // Toggle info boxes
        infoManual.classList.add('hidden');
        infoGateway.classList.remove('hidden');
        
        // Toggle button text
        btnManual.classList.add('hidden');
        btnGateway.classList.remove('hidden');
    } else {
        // Manual bank transfer: show upload, rekening, manual info
        uploadSection.classList.remove('hidden');
        buktiInput.required = true;
        if (imagePreview.src.includes('bukti-dana.png')) {
            imagePreview.src = '#';
            previewContainer.classList.add('hidden');
        }
        removePreviewBtn.classList.remove('hidden');
        
        rekeningSection.classList.remove('hidden');
        rekeningSelect.required = true;

        // Toggle info boxes
        infoManual.classList.remove('hidden');
        infoGateway.classList.add('hidden');
        
        // Toggle button text
        btnManual.classList.remove('hidden');
        btnGateway.classList.add('hidden');

        // Auto-select matching rekening if available (banks only)
        const options = rekeningSelect.options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].text.toLowerCase().includes(nama.toLowerCase().split(' ')[1] || nama.toLowerCase())) {
                rekeningSelect.value = options[i].value;
                break;
            }
        }
    }

    // Update selected info display
    document.getElementById('selected-metode-name').textContent = nama;
    document.getElementById('selected-metode-rekening').textContent = rekening || '-';
    document.getElementById('selected-metode-pemilik').textContent = pemilik || '-';
    document.getElementById('selected-metode-info').classList.remove('hidden');
    
    // Highlight button
    document.querySelectorAll('.metode-btn').forEach(btn => btn.classList.remove('ring-2', 'ring-green-500', 'ring-offset-2'));
    const selectedBtn = document.querySelector(`.metode-btn[data-metode-id="${id}"]`);
    if (selectedBtn) selectedBtn.classList.add('ring-2', 'ring-green-500', 'ring-offset-2');
}

function clearMetode() {
    document.getElementById('metode_pembayaran_id').value = '';
    document.getElementById('is_ewallet').value = '0';
    document.getElementById('selected-metode-info').classList.add('hidden');
    
    // Reset sections
    document.getElementById('upload-section').classList.remove('hidden');
    document.getElementById('bukti_transfer').required = true;
    document.getElementById('rekening-tujuan-section').classList.remove('hidden');
    document.getElementById('rekening_tujuan_id').required = true;
    
    // Clear preview
    document.getElementById('image-preview').src = '#';
    document.getElementById('preview-container').classList.add('hidden');
    document.getElementById('remove-preview').classList.remove('hidden');
    
    // Reset info boxes & button text
    document.getElementById('info-manual').classList.remove('hidden');
    document.getElementById('info-gateway').classList.add('hidden');
    document.getElementById('btn-text-manual').classList.remove('hidden');
    document.getElementById('btn-text-gateway').classList.add('hidden');

    document.querySelectorAll('.metode-btn').forEach(btn => btn.classList.remove('ring-2', 'ring-green-500', 'ring-offset-2'));
}
</script>
@endsection