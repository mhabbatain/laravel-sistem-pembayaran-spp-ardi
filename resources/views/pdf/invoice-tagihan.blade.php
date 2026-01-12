<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice Tagihan</title>
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

        .invoice-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #16a34a;
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
            width: 150px;
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

        .total-row {
            background-color: #f0fdf4 !important;
            font-weight: bold;
        }

        .total-row td {
            border-top: 2px solid #16a34a;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 11px;
        }

        .status-lunas {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-baru {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .summary-box {
            border: 1px solid #16a34a;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f0fdf4;
        }

        .summary-title {
            font-weight: bold;
            color: #16a34a;
            margin-bottom: 10px;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .summary-label {
            display: table-cell;
            width: 60%;
        }

        .summary-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }

        .summary-total {
            border-top: 1px solid #16a34a;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 14px;
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

        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $pengaturan->nama_sekolah ?? 'SEKOLAH' }}</h1>
            <p>{{ $pengaturan->alamat ?? 'Alamat Sekolah' }}</p>
            <p>Telp: {{ $pengaturan->no_telp ?? '-' }} | Email: {{ $pengaturan->email ?? '-' }}</p>
        </div>

        <div class="invoice-title">
            INVOICE TAGIHAN PEMBAYARAN
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">No. Invoice</span>
                <span class="info-value">: INV-{{ str_pad($tagihan->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">: {{ $tagihan->tanggal_tagihan->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Periode</span>
                <span class="info-value">: {{ $tagihan->bulan }} / {{ $tagihan->tahun }}</span>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Nama Siswa</span>
                <span class="info-value">: {{ $tagihan->siswa->nama }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NISN</span>
                <span class="info-value">: {{ $tagihan->siswa->nisn }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span class="info-value">: {{ $tagihan->siswa->kelas ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Wali Murid</span>
                <span class="info-value">: {{ $tagihan->siswa->waliMurid->user->name ?? '-' }}</span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Jenis Biaya</th>
                    <th class="text-right" style="width: 150px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tagihan->detailTagihan as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->biaya->nama_biaya }}</td>
                    <td class="text-right">Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-right">Total Tagihan</td>
                    <td class="text-right">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="summary-box">
            <div class="summary-title">Ringkasan Pembayaran</div>
            <div class="summary-row">
                <span class="summary-label">Total Tagihan</span>
                <span class="summary-value">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Jumlah Dibayar</span>
                <span class="summary-value" style="color: #16a34a;">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',',
                    '.') }}</span>
            </div>
            <div class="summary-row summary-total">
                <span class="summary-label">Sisa Tagihan</span>
                <span class="summary-value" style="color: #dc2626;">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',',
                    '.') }}</span>
            </div>
            <div class="summary-row" style="margin-top: 10px;">
                <span class="summary-label">Status</span>
                <span class="summary-value">
                    <span class="status-badge {{ $tagihan->status === 'lunas' ? 'status-lunas' : 'status-baru' }}">
                        {{ strtoupper($tagihan->status) }}
                    </span>
                </span>
            </div>
        </div>

        <div class="signature-section clearfix">
            <div class="signature-box">
                <p>{{ now()->format('d F Y') }}</p>
                <p>Bendahara</p>
                <div class="signature-line"></div>
                <p>(_________________)</p>
            </div>
        </div>

        <div class="footer" style="margin-top: 100px;">
            <p>Dokumen ini dicetak secara otomatis oleh sistem.</p>
            <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>