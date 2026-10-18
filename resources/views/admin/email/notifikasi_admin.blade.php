<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }} - Hotel Maintenance</title>
    <style>
        body { margin: 0; padding: 0; width: 100% !important; font-family: 'Helvetica', 'Arial', sans-serif; }
        .button {
            background-color: #1E3A5F;
            border-radius: 8px;
            color: #ffffff;
            display: inline-block;
            padding: 12px 24px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: 80%; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #C9D4E3;">

                    <tr>
                        <td align="center" style="background-color: #EAF0F7; padding: 30px 20px; border-bottom: 2px solid #C9D4E3;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: bold; color: #16243A; letter-spacing: 1px;">HOTEL MAINTENANCE</h1>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #52627A; text-transform: uppercase; letter-spacing: 2px;">Notifikasi Admin</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 40px;">
                            <h2 style="margin: 0 0 15px 0; font-size: 20px; color: #1E3A5F;">{{ $judul }}</h2>
                            <p style="margin: 0 0 20px 0; font-size: 14px; color: #666666; line-height: 1.6;">{{ $pesan }}</p>

                            <table border="0" cellpadding="8" cellspacing="0" width="100%" style="font-size: 14px; border-collapse: collapse; margin-bottom: 25px;">
                                @foreach ($detail as $label => $nilai)
                                    <tr style="border-bottom: 1px solid #E3E9F2;">
                                        <td style="color: #52627A; width: 35%; vertical-align: top;">{{ $label }}</td>
                                        <td style="color: #16243A; font-weight: bold;">{{ $nilai }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <a href="{{ $link }}" target="_blank" class="button" style="text-decoration:none; color:white;">{{ $labelLink }}</a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #F9FAFC; padding: 20px; border-top: 1px solid #DBE3EE;">
                            <p style="margin: 0; font-size: 11px; color: #52627A;">
                                &copy; {{ date('Y') }} Sistem Maintenance Hotel &middot; Email otomatis, mohon tidak membalas.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
