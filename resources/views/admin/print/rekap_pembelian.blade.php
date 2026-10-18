<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pembelian {{ $awal->locale('id')->translatedFormat('d F Y') }} s/d {{ $akhir->locale('id')->translatedFormat('d F Y') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #16243A; }
        h1 { font-size: 18px; margin: 0; }
        .sub { color: #52627A; margin: 2px 0 14px 0; }
        table { width: 100%; border-collapse: collapse; }
        .data th { background: #EAF0F7; border: 1px solid #C9D4E3; padding: 6px; text-align: left; font-size: 10px; text-transform: uppercase; }
        .data td { border: 1px solid #C9D4E3; padding: 6px; vertical-align: top; }
        .kanan { text-align: right; }
        .total td { font-weight: bold; background: #EAF0F7; }
        .footer { margin-top: 16px; color: #52627A; font-size: 9px; }
    </style>
</head>
<body>
    <h1>Rekap Pembelian Barang &amp; Jasa</h1>
    <p class="sub">Sistem Maintenance Hotel &middot; Periode {{ $awal->locale('id')->translatedFormat('d F Y') }} s/d {{ $akhir->locale('id')->translatedFormat('d F Y') }}</p>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 10%;">Tanggal</th>
                <th>Barang/Jasa</th>
                <th style="width: 15%;">Mitra</th>
                <th style="width: 15%;">Untuk Laporan</th>
                <th style="width: 6%;">Jml</th>
                <th style="width: 13%;" class="kanan">Total</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembelian_all as $pembelian)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pembelian->created_at?->format('d-m-Y') }}</td>
                    <td>{{ $pembelian->nama_barang }} ({{ $pembelian->jenis }})</td>
                    <td>{{ $pembelian->mitra_bisnis->nama_mitra ?? '-' }}</td>
                    <td>{{ $pembelian->laporan->lokasi_fasilitas ?? '-' }}</td>
                    <td>{{ $pembelian->jumlah_beli }}</td>
                    <td class="kanan">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $pembelian->bukti_bayar ? 'Sudah Bayar' : 'Belum Bayar' }}<br>{{ $pembelian->status_pembelian }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align: center; font-style: italic;">Tidak ada pembelian pada periode ini.</td></tr>
            @endforelse
        </tbody>
        @if ($pembelian_all->isNotEmpty())
            <tfoot>
                <tr class="total">
                    <td colspan="6">Total pembelian yang sudah dibayar</td>
                    <td class="kanan">Rp {{ number_format($total_dibayar, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <p class="footer">Dicetak pada {{ now()->locale('id')->translatedFormat('d F Y H:i') }} oleh {{ auth()->user()->nama }}</p>
</body>
</html>
