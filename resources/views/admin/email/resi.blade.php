<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Pembelian - Hotel Maintenance</title>
    <style>
        /* Reset Styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        
        /* General */
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; font-family: 'Helvetica', 'Arial', sans-serif; }
        
        /* Mobile Responsive */
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .col-split { display: block !important; width: 100% !important; padding-bottom: 10px; }
        }
    </style>
</head>
<body style=" margin: 0; padding: 0;">

    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        Terima kasih atas pembelian Anda. Berikut adalah detail resi #{{ $resi->resi }}.
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 20px 0;">
        <tr>
            <td align="center">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: 80%; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(22,36,58,0.1); border: 1px solid #C9D4E3;">
                    
                    <tr>
                        <td align="center" style="background-color: #EAF0F7; padding: 30px 20px; border-bottom: 2px solid #C9D4E3;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: bold; color: #16243A; letter-spacing: 1px;">HOTEL MAINTENANCE</h1>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #52627A; text-transform: uppercase; letter-spacing: 2px;">Official Receipt</p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 30px 20px 10px 20px;">
                            <img src="https://cdn-icons-png.flaticon.com/512/7518/7518748.png" width="60" alt="Check" style="display: block; margin-bottom: 15px; opacity: 0.8;">
                            <h2 style="margin: 0; font-size: 20px; color: #1E3A5F;">Pembayaran Berhasil!</h2>
                            <p style="margin: 10px 0 0 0; font-size: 14px; color: #666666; line-height: 1.5;">
                                Terima kasih <strong>{{ $resi->nama ?? 'Pelanggan' }}</strong>,<br>
                                Transaksi Anda telah berhasil dicatat oleh sistem kami.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F7F9FC; border-radius: 8px; border: 1px dashed #C9D4E3;">
                                <tr>
                                    <td align="center" style="padding: 15px;">
                                        <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #52627A; font-weight: bold;">NOMOR RESI</p>
                                        <p style="margin: 5px 0 0 0; font-size: 18px; font-weight: bold; color: #16243A; letter-spacing: 1px;">{{ $resi->resi }}</p>
                                        <p style="margin: 5px 0 0 0; font-size: 12px; color: #888;">{{ optional($resi->pembelian->created_at)->format('d M Y, H:i') }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 1px solid #eee;">
                                        <p style="margin: 0; font-size: 12px; color: #52627A; text-transform: uppercase;">Barang / Jasa</p>
                                        <p style="margin: 2px 0 0 0; font-size: 15px; font-weight: bold; color: #16243A;">{{ $resi->pembelian->nama_barang ?? '-' }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                        <table width="100%">
                                            <tr>
                                                <td width="50%" class="col-split">
                                                    <p style="margin: 0; font-size: 11px; color: #52627A;">MITRA PENYEDIA</p>
                                                    <p style="margin: 2px 0 0 0; font-size: 13px; color: #333;">{{ $resi->mitra_bisnis->nama_mitra ?? '-' }}</p>
                                                </td>
                                                <td width="50%" class="col-split">
                                                    <p style="margin: 0; font-size: 11px; color: #52627A;">KONTAK PENJUAL / MITRA</p>
                                                    <p style="margin: 2px 0 0 0; font-size: 13px; color: #333;">{{ $resi->mitra_bisnis->kontak ?? '-' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                        <p style="margin: 0; font-size: 11px; color: #52627A;">ALAMAT PENGIRIMAN</p>
                                        <p style="margin: 2px 0 0 0; font-size: 13px; color: #333; line-height: 1.4;">{{ $resi->alamat ?? '-' }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #1E3A5F; border-radius: 8px; color: #ffffff;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <table width="100%">
                                            <tr>
                                                <td align="left" style="font-size: 13px; font-weight: bold; opacity: 0.9;">TOTAL DIBAYAR</td>
                                                <td align="right" style="font-size: 18px; font-weight: bold;">
                                                    Rp {{ number_format($resi->pembelian->total_harga ?? 0, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #F9FAFC; padding: 20px; border-top: 1px solid #DBE3EE;">
                            <p style="margin: 0; font-size: 11px; color: #52627A;">
                                &copy; {{ date('Y') }} Sistem Maintenance Hotel.<br>
                                Email ini dibuat secara otomatis, mohon tidak membalas.
                            </p>
                        </td>
                    </tr>

                </table>
                <div style="height: 40px;">&nbsp;</div>

            </td>
        </tr>
    </table>

</body>
</html>