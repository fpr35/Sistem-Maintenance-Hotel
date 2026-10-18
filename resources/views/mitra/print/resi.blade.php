<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Pembelian #{{ $resi->resi_id }}</title>
    <style>
        /* Reset & Base Styles */
        @page {
            margin: 0cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #ffffff;
            color: #16243A; /* Coklat Tua */
            font-size: 13px;
            line-height: 1.4;
            margin-top: 3cm;
            margin-bottom: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
        }

        /* Helper Classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        .text-teal { color: #1E3A5F; }
        .text-gold { color: #52627A; }

        /* Tables (DOMPDF Layout Engine) */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        /* HEADER */
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
            background-color: #F4F6FA; /* Background Cream */
            border-bottom: 2px solid #C9D4E3;
            padding: 0 2cm;
            line-height: 3cm; /* Vertical Center hack */
        }
        
        .header-table td {
            vertical-align: middle;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #16243A;
            letter-spacing: 1px;
        }
        
        .resi-badge {
            background-color: #1E3A5F;
            color: #ffffff;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            line-height: 1.2;
        }

        /* CONTENT SECTIONS */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #52627A; /* Gold/Brown */
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 1px solid #C9D4E3;
            padding-bottom: 5px;
            margin-bottom: 15px;
            margin-top: 30px;
        }

        /* INFO TABLE */
        .info-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .label-col {
            width: 140px;
            color: #777;
            font-size: 12px;
        }
        .value-col {
            font-weight: 600;
            color: #16243A;
        }

        /* ITEM TABLE (INVOICE STYLE) */
        .item-table {
            margin-top: 20px;
            border: 1px solid #C9D4E3;
        }
        .item-table th {
            background-color: #EAF0F7;
            color: #52627A;
            font-size: 11px;
            text-transform: uppercase;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #C9D4E3;
        }
        .item-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
        }
        .item-table tr:last-child td {
            border-bottom: none;
        }

        /* TOTAL SECTION */
        .total-container {
            margin-top: 30px;
            text-align: right;
        }
        .total-box {
            display: inline-block;
            background-color: #1E3A5F; /* Teal Main */
            color: white;
            padding: 15px 30px;
            border-radius: 6px;
        }
        .total-label {
            font-size: 11px;
            text-transform: uppercase;
            opacity: 0.9;
            display: block;
            margin-bottom: 5px;
        }
        .total-value {
            font-size: 20px;
            font-weight: bold;
        }

        /* FOOTER */
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #F4F6FA;
            text-align: center;
            line-height: 1.5;
            padding-top: 15px;
            color: #52627A;
            font-size: 10px;
            border-top: 1px solid #C9D4E3;
        }

        /* Watermark (Optional decoration) */
        .watermark {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(201,212,227, 0.1); /* Very light gold */
            z-index: -1;
            font-weight: bold;
            border: 5px solid rgba(201,212,227, 0.1);
            padding: 20px;
        }
    </style>
</head>
<body> 
    <header>
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    <div class="logo">HOTEL MAINTENANCE</div>
                    <div style="font-size: 1px; color: #52627A; margin-top: -5px;">.</div>
                </td>
                <td style="width: 40%; text-align: right;">
                    <div class="resi-badge">
                        RESI #{{ $resi->resi_id }}
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <div style="font-weight: bold; margin-bottom: 4px;">Terima Kasih!</div>
        <div>Dokumen ini adalah bukti transaksi yang sah.</div>
        <div>&copy; {{ date('Y') }} Hotel Maintenance System. All rights reserved.</div>
    </footer>

    <main>
        <div class="watermark">LUNAS</div>

        <div class="section-title">Informasi Penerima</div>
        <table class="info-table">
            <tr>
                <td class="label-col">Nama Penerima</td>
                <td class="value-col">{{ $resi->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Nomor Kontak</td>
                <td class="value-col">{{ $resi->kontak ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Alamat Pengiriman</td>
                <td class="value-col" style="line-height: 1.6;">
                    {{ $resi->alamat ?? '-' }}
                </td>
            </tr>
            <tr>
                <td class="label-col">Tanggal Transaksi</td>
                <td class="value-col">
                    {{ optional($resi->pembelian->created_at)->translatedFormat('l, d F Y') ?? '-' }}
                    <span style="font-weight: normal; font-size: 11px; color: #777;">
                        (Pukul {{ optional($resi->pembelian->created_at)->format('H:i') }})
                    </span>
                </td>
            </tr>
        </table>

        <div class="section-title">Rincian Barang & Jasa</div>
        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Nama Item</th>
                    <th style="width: 15%; text-align: center;">Jenis Item</th>
                    <th style="width: 30%; text-align: center;">Mitra Penyedia</th>
                    <th style="width: 15%; text-align: center;">Jumlah</th>
                    <th style="width: 15%; text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight: bold; font-size: 14px; color: #1E3A5F;">
                            {{ $resi->pembelian->nama_barang ?? 'Item Tidak Ditemukan' }}
                        </div>
                        <div style="font-size: 10px; color: #888; margin-top: 4px;">
                            ID Transaksi: P-{{ $resi->pembelian->pembelian_id ?? '000' }}
                        </div>
                    </td>
                    <td style="text-align: center;">
                        {{ $resi->pembelian->jenis ?? '-' }}
                    </td>
                    <td style="text-align: center;">
                        {{ $resi->mitra_bisnis->nama_mitra ?? '-' }}
                    </td>
                    
                    <td style="text-align: center;">
                        {{ $resi->pembelian->jumlah_beli ?? '1' }} Pcs
                    </td>
                    <td style="text-align: right;">
                        <span style="color: #27ae60; font-weight: bold; font-size: 11px;">BERHASIL</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="total-container">
            <div class="total-box">
                <span class="total-label">Total Yang Dibayarkan</span>
                <span class="total-value">
                    Rp {{ number_format($resi->pembelian->total_harga ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div style="margin-top: 50px; padding: 15px; background-color: #fcfcfc; border-left: 3px solid #C9D4E3; font-size: 11px; color: #777;">
            <strong>Catatan Penting:</strong>
            <br>
            Harap simpan dokumen ini sebagai bukti klaim garansi atau retur barang jika terjadi ketidaksesuaian. 
            Hubungi Customer Service kami jika ada kendala.
        </div>
    </main>
</body>
</html>