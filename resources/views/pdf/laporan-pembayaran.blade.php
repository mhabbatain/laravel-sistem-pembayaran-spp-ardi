<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 16px;
            color: #16a34a;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 9px;
            color: #666;
        }

        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #16a34a;
        }

        .report-period {
            text-align: center;
            font-size: 10px;
            margin-bottom: 15px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 9px;
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

        .summary-section {
            margin-top: 15px;
            border: 1px solid #16a34a;
            border-radius: 5px;
            padding: 10px;
            background-color: #f0fdf4;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .summary-label {
            display: table-cell;
            width: 70%;
            font-weight: bold;
        }

        .summary-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            color: #16a34a;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .signature-section {
            margin-top: 30px;
        }

        .signature-box {
            float: right;
            width: 180px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 50px;
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

        <div class="report-title">
            LAPORAN PEMBAYARAN
        </div>
        <div class="report-period">
            @if($dari && $sampai)
            Periode: {{ \Carbon\Carbon::parse($dari)->format('d F Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d
            F Y') }}
            @elseif($dari)
            Mulai dari: {{ \Carbon\Carbon::parse($dari)->format('d F Y') }}
            @elseif($sampai)
            Sampai dengan: {{ \Carbon\Carbon::parse($sampai)->format('d F Y') }}
            @else
            Semua Periode
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 70px;">Tanggal</th>
                    <th>Nama Siswa</th>
                    <th style="width: 80px;">NISN</th>
                    <th style="width: 60px;">Periode</th>
                    <th>Rekening</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @forelse($pembayaran as $index => $item)
                @php $total += $item->jumlah_bayar; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_pembayaran?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $item->tagihan->siswa->nama ?? '-' }}</td>
                    <td>{{ $item->tagihan->siswa->nisn ?? '-' }}</td>
                    <td>{{ $item->tagihan->bulan ?? '-' }}/{{ $item->tagihan->tahun ?? '-' }}</td>
                    <td>{{ $item->rekeningTujuan->nama_bank ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f0fdf4; font-weight: bold;">
                    <td colspan="6" class="text-right">Total Pembayaran</td>
                    <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="summary-section">
            <div class="summary-row">
                <span class="summary-label">Total Transaksi</span>
                <span class="summary-value">{{ $pembayaran->count() }} transaksi</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Pembayaran</span>
                <span class="summary-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
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

        <div class="footer" style="margin-top: 80px;">
            <p>Laporan ini dicetak secara otomatis oleh sistem.</p>
            <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>