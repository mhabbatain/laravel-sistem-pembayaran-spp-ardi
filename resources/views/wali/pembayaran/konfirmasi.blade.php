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
                    <p class="font-medium text-green-900">Rp {{ number_format($tagihan->total_tagihan_terpilih, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-green-700">Sisa Tagihan (Komponen Dipilih)</p>
                    <p class="font-medium text-red-600 text-lg">Rp {{ number_format($tagihan->sisa_tagihan_terpilih, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('wali.pembayaran.store-konfirmasi', $tagihan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Jumlah Bayar (Hidden, calculated automatically) -->
            <input type="hidden" name="jumlah_bayar" id="jumlah_bayar" value="0">

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
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis Biaya</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Terbayar</th>
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
                                        {{ old('detail_ids') ? (in_array($detail->id, old('detail_ids')) ? 'checked' : '') : ($detail->biaya->is_default ? 'checked' : '') }}>
                                    @else
                                    <span class="text-green-600 font-semibold">✓ Lunas</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $detail->biaya->nama_biaya }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right">Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-green-600 text-right">Rp {{ number_format($detail->jumlah_dibayar, 0, ',', '.') }}</td>
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
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total yang akan dibayar:</td>
                                <td class="px-4 py-3 text-right text-base font-bold text-green-600" id="total-terpilih">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Rekening Tujuan -->
            <div>
                <label for="rekening_tujuan_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Rekening Tujuan <span class="text-red-500">*</span>
                </label>
                <select name="rekening_tujuan_id" id="rekening_tujuan_id" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('rekening_tujuan_id') ? 'border-red-500' : 'border-gray-300' }}">
                    <option value="">Pilih Rekening</option>
                    @foreach($rekeningSekolah as $rekening)
                    <option value="{{ $rekening->id }}" {{ old('rekening_tujuan_id') == $rekening->id ? 'selected' : '' }}>
                        {{ $rekening->nama_bank }} - {{ $rekening->nomor_rekening }} ({{ $rekening->atas_nama }})
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
                <input type="file" name="bukti_transfer" id="bukti_transfer" accept="image/*" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('bukti_transfer') ? 'border-red-500' : 'border-gray-300' }}">
                <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
                @error('bukti_transfer')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
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

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <a href="{{ route('wali.tagihan.show', $tagihan) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" id="submit-btn"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    Kirim Konfirmasi
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
});
</script>
@endsection
