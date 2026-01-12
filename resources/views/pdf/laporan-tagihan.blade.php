<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan</title>
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

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 8px;
        }

        .status-lunas {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-baru {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .status-sebagian {
            background-color: #fef3c7;
            color: #d97706;
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
            LAPORAN TAGIHAN
        </div>
        <div class="report-period">
            @if($bulan && $tahun)
            Periode: {{ $bulan }} / {{ $tahun }}
            @elseif($tahun)
            Tahun: {{ $tahun }}
            @else
            Semua Periode
            @endif
            @if($status)
            | Status: {{ ucfirst($status) }}
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 70px;">Tanggal</th>
                    <th>Nama Siswa</th>
                    <th style="width: 80px;">NISN</th>
                    <th style="width: 55px;">Periode</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Dibayar</th>
                    <th class="text-right">Sisa</th>
                    <th class="text-center" style="width: 55px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                $totalTagihan = 0;
                $totalDibayar = 0;
                $totalSisa = 0;
                @endphp
                @forelse($tagihan as $index => $item)
                @php
                $totalTagihan += $item->total_tagihan;
                $totalDibayar += $item->jumlah_bayar;
                $totalSisa += $item->sisa_tagihan;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_tagihan?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $item->siswa->nama ?? '-' }}</td>
                    <td>{{ $item->siswa->nisn ?? '-' }}</td>
                    <td>{{ $item->bulan }}/{{ $item->tahun }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->sisa_tagihan, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->status === 'lunas')
                        <span class="status-badge status-lunas">LUNAS</span>
                        @elseif($item->jumlah_bayar > 0)
                        <span class="status-badge status-sebagian">SEBAGIAN</span>
                        @else
                        <span class="status-badge status-baru">BELUM</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data tagihan</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f0fdf4; font-weight: bold;">
                    <td colspan="5" class="text-right">Total</td>
                    <td class="text-right">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="summary-section">
            <div class="summary-row">
                <span class="summary-label">Total Tagihan</span>
                <span class="summary-value">{{ $tagihan->count() }} tagihan</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tagihan Lunas</span>
                <span class="summary-value" style="color: #16a34a;">{{ $tagihan->where('status', 'lunas')->count() }}
                    tagihan</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tagihan Belum Lunas</span>
                <span class="summary-value" style="color: #dc2626;">{{ $tagihan->where('status', '!=', 'lunas')->count()
                    }} tagihan</span>
            </div>
            <div class="summary-row" style="border-top: 1px solid #16a34a; padding-top: 5px; margin-top: 5px;">
                <span class="summary-label">Total Nilai Tagihan</span>
                <span class="summary-value">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Terbayar</span>
                <span class="summary-value" style="color: #16a34a;">Rp {{ number_format($totalDibayar, 0, ',', '.')
                    }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Sisa Tagihan</span>
                <span class="summary-value" style="color: #dc2626;">Rp {{ number_format($totalSisa, 0, ',', '.')
                    }}</span>
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