<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            color: #16a34a;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .kwitansi-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #16a34a;
        }

        .kwitansi-number {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .info-label {
            display: table-cell;
            width: 180px;
            font-weight: bold;
            color: #555;
        }

        .info-value {
            display: table-cell;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #16a34a;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #f0fdf4 !important;
            font-weight: bold;
        }

        .total-row td {
            border-top: 2px solid #16a34a;
        }

        .amount-box {
            border: 2px solid #16a34a;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f0fdf4;
            text-align: center;
        }

        .amount-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #16a34a;
        }

        .amount-words {
            font-size: 11px;
            font-style: italic;
            color: #555;
            margin-top: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 11px;
        }

        .status-dikonfirmasi {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-ditolak {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .signature-section {
            margin-top: 40px;
        }

        .signature-row {
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
            width: 150px;
            display: inline-block;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(22, 163, 74, 0.1);
            font-weight: bold;
            z-index: -1;
        }

        .payment-info {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .payment-info-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    @if($pembayaran->status_konfirmasi === 'dikonfirmasi')
    <div class="watermark">LUNAS</div>
    @endif

    <div class="container">
        <div class="header">
            <h1>{{ $pengaturan->nama_sekolah ?? 'SEKOLAH' }}</h1>
            <p>{{ $pengaturan->alamat ?? 'Alamat Sekolah' }}</p>
            <p>Telp: {{ $pengaturan->no_telp ?? '-' }} | Email: {{ $pengaturan->email ?? '-' }}</p>
        </div>

        <div class="kwitansi-title">
            KWITANSI PEMBAYARAN
        </div>
        <div class="kwitansi-number">
            No: KWT-{{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}
        </div>

        <div class="amount-box">
            <div class="amount-label">Jumlah Pembayaran</div>
            <div class="amount-value">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
            <div class="amount-words">{{ ucwords(\App\Helpers\Terbilang::make($pembayaran->jumlah_bayar)) }} Rupiah
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Diterima dari</span>
                <span class="info-value">: {{ $pembayaran->tagihan->siswa->waliMurid->user->name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Siswa</span>
                <span class="info-value">: {{ $pembayaran->tagihan->siswa->nama }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NISN</span>
                <span class="info-value">: {{ $pembayaran->tagihan->siswa->nisn }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span class="info-value">: {{ $pembayaran->tagihan->siswa->kelas ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Periode Tagihan</span>
                <span class="info-value">: {{ $pembayaran->tagihan->bulan }} / {{ $pembayaran->tagihan->tahun }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Pembayaran</span>
                <span class="info-value">: {{ $pembayaran->tanggal_pembayaran?->format('d F Y') ?? '-' }}</span>
            </div>
        </div>

        <div class="payment-info">
            <div class="payment-info-title">Informasi Pembayaran</div>
            <div class="info-row">
                <span class="info-label">Metode Pembayaran</span>
                <span class="info-value">: {{ ucfirst($pembayaran->metode_pembayaran ?? 'Transfer') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rekening Tujuan</span>
                <span class="info-value">: {{ $pembayaran->rekeningTujuan->nama_bank ?? '-' }} - {{
                    $pembayaran->rekeningTujuan->nomor_rekening ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">:
                    <span class="status-badge status-{{ $pembayaran->status_konfirmasi }}">
                        {{ strtoupper($pembayaran->status_konfirmasi) }}
                    </span>
                </span>
            </div>
            @if($pembayaran->status_konfirmasi === 'dikonfirmasi')
            <div class="info-row">
                <span class="info-label">Dikonfirmasi pada</span>
                <span class="info-value">: {{ $pembayaran->tanggal_konfirmasi?->format('d F Y H:i') ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dikonfirmasi oleh</span>
                <span class="info-value">: {{ $pembayaran->dikonfirmasiOleh->name ?? '-' }}</span>
            </div>
            @endif
        </div>

        <p style="margin-bottom: 10px;"><strong>Untuk pembayaran:</strong></p>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Jenis Biaya</th>
                    <th class="text-right" style="width: 150px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayaran->tagihan->detailTagihan as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->biaya->nama_biaya }}</td>
                    <td class="text-right">Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <p>Pembayar</p>
                <div class="signature-line"></div>
                <p>{{ $pembayaran->tagihan->siswa->waliMurid->user->name ?? '(_________________)' }}</p>
    </div>
    <div class="signature-box">
        <p>{{ now()->format('d F Y') }}</p>
        <p>Bendahara</p>
        <div class="signature-line"></div>
        <p>(_________________)</p>
    </div>
    </div>
    </div>

    <div class="footer">
        <p>Kwitansi ini merupakan bukti pembayaran yang sah.</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
    </div>
</body>

</html>