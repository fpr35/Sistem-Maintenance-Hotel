<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Laporan Kerusakan {{ $awal->locale('id')->translatedFormat('d F Y') }} s/d {{ $akhir->locale('id')->translatedFormat('d F Y') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #16243A; }
        h1 { font-size: 18px; margin: 0; }
        .sub { color: #52627A; margin: 2px 0 14px 0; }
        table { width: 100%; border-collapse: collapse; }
        .ringkasan td { border: 1px solid #C9D4E3; padding: 8px; text-align: center; width: 20%; }
        .ringkasan .angka { font-size: 16px; font-weight: bold; color: #1E3A5F; }
        .data th { background: #EAF0F7; border: 1px solid #C9D4E3; padding: 6px; text-align: left; font-size: 10px; text-transform: uppercase; }
        .data td { border: 1px solid #C9D4E3; padding: 6px; vertical-align: top; }
        .footer { margin-top: 16px; color: #52627A; font-size: 9px; }
    </style>
</head>
<body>
    <h1>Rekap Laporan Kerusakan</h1>
    <p class="sub">Sistem Maintenance Hotel &middot; Periode {{ $awal->locale('id')->translatedFormat('d F Y') }} s/d {{ $akhir->locale('id')->translatedFormat('d F Y') }}</p>

    {{-- Ringkasan jumlah per status --}}
    <table class="ringkasan" style="margin-bottom: 14px;">
        <tr>
            <td><div>Total</div><div class="angka">{{ $laporan_all->count() }}</div></td>
            @foreach ($ringkasan as $status => $jumlah)
                <td><div>{{ $status }}</div><div class="angka">{{ $jumlah }}</div></td>
            @endforeach
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 11%;">Tanggal</th>
                <th style="width: 16%;">Lokasi</th>
                <th>Deskripsi Kerusakan</th>
                <th style="width: 16%;">Pelapor</th>
                <th style="width: 11%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan_all as $laporan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d-m-Y') }}</td>
                    <td>{{ $laporan->lokasi_fasilitas }}</td>
                    <td>{{ $laporan->deskripsi_kerusakan }}</td>
                    <td>{{ $laporan->pelapor->nama ?? '-' }}{{ $laporan->pelapor ? ' (' . $laporan->pelapor->role . ')' : '' }}</td>
                    <td>{{ $laporan->status_laporan }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align: center; font-style: italic;">Tidak ada laporan kerusakan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">Dicetak pada {{ now()->locale('id')->translatedFormat('d F Y H:i') }} oleh {{ auth()->user()->nama }}</p>
</body>
</html>
