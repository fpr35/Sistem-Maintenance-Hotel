<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Hotel Maintenance</title>
    <style>
        /* Reset Styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        
        /* General */
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; font-family: 'Helvetica', 'Arial', sans-serif; }
        
        /* Button Style */
        .button {
            background-color: #1E3A5F;
            border-radius: 8px;
            color: #ffffff;
            display: inline-block;
            padding: 12px 24px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Mobile Responsive */
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">

    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        Selamat, akun Mitra Anda telah berhasil diverifikasi. Silakan login sekarang.
    </div>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 0;">
        <tr>
            <td align="center">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: 80%; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(22,36,58,0.1); border: 1px solid #C9D4E3;">
                    
                    <tr>
                        <td align="center" style="background-color: #EAF0F7; padding: 30px 20px; border-bottom: 2px solid #C9D4E3;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: bold; color: #16243A; letter-spacing: 1px;">HOTEL MAINTENANCE</h1>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #52627A; text-transform: uppercase; letter-spacing: 2px;">Mitra Partnership</p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 40px 20px 10px 20px;">
                            <img src="https://cdn-icons-png.flaticon.com/512/4005/4005750.png" width="80" alt="Lock Shield" style="display: block; opacity: 0.9;">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 10px 40px 30px 40px;">
                            <h2 style="margin: 0 0 15px 0; font-size: 24px; color: #1E3A5F;">Reset Password</h2>
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; font-weight: bold; color: #16243A;">
                                Halo, {{ $user->nama }}
                            </p>
                            
                            <p style="margin: 0 0 30px 0; font-size: 14px; color: #666666; line-height: 1.6;">
                                Anda telah meminta untuk mereset password akun Anda. Silakan klik tombol di bawah ini untuk membuat password baru.
                            </p>

                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="border-radius: 8px;">
                                        <a href="{{ route('new_password', [$token, $user->user_id]) }}" target="_blank" class="button" style="text-decoration:none; color:white;">
                                            Buat Password Baru  
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top: 30px; font-size: 12px; color: #999; line-height: 1.4;">
                                Jika tombol di atas tidak berfungsi, silakan salin dan tempel tautan berikut di browser Anda:<br>
                                <span style="color: #1E3A5F;">{{ route('new_password', [$token, $user->user_id]) }}</span>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #F9FAFC; padding: 20px; border-top: 1px solid #DBE3EE;">
                            <p style="margin: 0 0 5px 0; font-size: 11px; color: #52627A; font-weight: bold;">
                                &copy; {{ date('Y') }} Sistem Maintenance Hotel
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #52627A;">
                                Email ini dikirim secara otomatis. Mohon jangan membalas email ini.
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