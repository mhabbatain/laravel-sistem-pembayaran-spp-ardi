<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kartu SPP - {{ $siswa->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
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
            font-size: 10px;
            color: #666;
        }

        .card-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #16a34a;
        }

        .student-info {
            border: 1px solid #16a34a;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f0fdf4;
        }

        .student-info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .student-info-label {
            display: table-cell;
            width: 120px;
            font-weight: bold;
            color: #555;
        }

        .student-info-value {
            display: table-cell;
        }

        .summary-section {
            margin-bottom: 20px;
        }

        .summary-box {
            display: inline-block;
            width: 32%;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
        }

        .summary-box.total {
            background-color: #f3f4f6;
        }

        .summary-box.paid {
            background-color: #dcfce7;
        }

        .summary-box.remaining {
            background-color: #fee2e2;
        }

        .summary-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }

        .summary-value.total {
            color: #374151;
        }

        .summary-value.paid {
            color: #16a34a;
        }

        .summary-value.remaining {
            color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
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
            padding: 3px 10px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 9px;
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

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .page-break {
            page-break-after: always;
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

        <div class="card-title">
            KARTU SPP SISWA
        </div>

        <div class="student-info">
            <div class="student-info-row">
                <span class="student-info-label">Nama Siswa</span>
                <span class="student-info-value">: {{ $siswa->nama }}</span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">NISN</span>
                <span class="student-info-value">: {{ $siswa->nisn }}</span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">Kelas</span>
                <span class="student-info-value">: {{ $siswa->kelas ?? '-' }}</span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">Jurusan</span>
                <span class="student-info-value">: {{ $siswa->jurusan ?? '-' }}</span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">Alamat</span>
                <span class="student-info-value">: {{ $siswa->alamat ?? '-' }}</span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">Wali Murid</span>
                <span class="student-info-value">: {{ $siswa->waliMurid->user->name ?? '-' }}</span>
            </div>
        </div>

        @php
        $totalTagihan = $siswa->tagihan->sum('total_tagihan');
        $totalBayar = $siswa->tagihan->sum('jumlah_bayar');
        $sisaTagihan = $siswa->tagihan->sum('sisa_tagihan');
        @endphp

        <div class="summary-section" style="text-align: center;">
            <div class="summary-box total">
                <div class="summary-label">Total Tagihan</div>
                <div class="summary-value total">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-box paid">
                <div class="summary-label">Sudah Dibayar</div>
                <div class="summary-value paid">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div>
            </div>
            <div class="summary-box remaining">
                <div class="summary-label">Sisa Tagihan</div>
                <div class="summary-value remaining">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</div>
            </div>
        </div>

        <h3 style="margin-bottom: 10px; color: #374151;">Riwayat Tagihan</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 80px;">Periode</th>
                    <th class="text-right">Total Tagihan</th>
                    <th class="text-right">Dibayar</th>
                    <th class="text-right">Sisa</th>
                    <th class="text-center" style="width: 70px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa->tagihan as $index => $tagihan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tagihan->bulan }}/{{ $tagihan->tahun }}</td>
                    <td class="text-right">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($tagihan->status === 'lunas')
                        <span class="status-badge status-lunas">LUNAS</span>
                        @elseif($tagihan->jumlah_bayar > 0)
                        <span class="status-badge status-sebagian">SEBAGIAN</span>
                        @else
                        <span class="status-badge status-baru">BELUM</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada tagihan</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>Kartu SPP ini dicetak secara otomatis oleh sistem.</p>
            <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>

</html>