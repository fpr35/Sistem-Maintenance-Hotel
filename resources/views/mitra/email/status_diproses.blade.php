<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orderan Diproses - Hotel Maintenance</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; font-family: 'Helvetica', 'Arial', sans-serif; }
        
        .button {
            background-color: #1D7BB8; /* Biru Status Diproses */
            border-radius: 8px;
            color: #ffffff;
            display: inline-block;
            padding: 12px 24px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">

    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        Update Pesanan: Orderan #{{ $pembelian->pembelian_id }} sedang diproses.
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: 80%; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(22,36,58,0.1); border: 1px solid #C9D4E3;">
                    
                    <tr>
                        <td align="center" style="background-color: #EAF0F7; padding: 30px 20px; border-bottom: 2px solid #C9D4E3;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: bold; color: #16243A; letter-spacing: 1px;">HOTEL MAINTENANCE</h1>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #52627A; text-transform: uppercase; letter-spacing: 2px;">Update Status Pesanan</p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 40px 20px 10px 20px;">
                            <img src="https://cdn-icons-png.flaticon.com/512/2720/2720549.png" width="80" alt="Processing" style="display: block; opacity: 0.9;">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 10px 40px 30px 40px;">
                            <h2 style="margin: 0 0 15px 0; font-size: 24px; color: #1D7BB8;">Sedang Diproses</h2>
                            
                            <p style="margin: 0 0 20px 0; font-size: 14px; color: #666666; line-height: 1.6;">
                                Halo, status pesanan Anda dengan kode <strong style="color: #16243A;">#{{ $pembelian->pembelian_id }}</strong> saat ini telah berubah menjadi <strong>Diproses</strong>.
                            </p>
                            
                            <div style="background-color: #F0F9FF; border: 1px dashed #1D7BB8; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
                                <p style="margin: 0; font-size: 13px; color: #1D7BB8;">
                                    Mitra kami sedang menyiapkan barang/jasa yang Anda pesan. Mohon menunggu update selanjutnya.
                                </p>
                            </div>

                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/') }}" target="_blank" class="button" style="text-decoration:none;color:white;">
                                            Cek Detail di Dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #F9FAFC; padding: 20px; border-top: 1px solid #DBE3EE;">
                            <p style="margin: 0 0 5px 0; font-size: 11px; color: #52627A; font-weight: bold;">
                                &copy; {{ date('Y') }} Sistem Maintenance Hotel
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #52627A;">
                                Email notifikasi otomatis.
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