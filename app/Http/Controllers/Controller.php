<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Models\userModel;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // ========================================================================
    // FUNCTION: pesanError
    // TUJUAN: Membuat pesan error yang aman untuk ditampilkan ke user.
    //         - Detail error selalu dicatat ke storage/logs/laravel.log
    //         - Detail teknis hanya ditampilkan jika APP_DEBUG=true (mode
    //           development), agar struktur database tidak bocor saat online.
    // ========================================================================
    protected function pesanError(\Throwable $e, string $pesan = 'Terjadi kesalahan'): string
    {
        report($e);

        if (config('app.debug')) {
            return $pesan . ': ' . $e->getMessage();
        }

        return $pesan . '. Silahkan coba lagi atau hubungi admin.';
    }

    // ========================================================================
    // FUNCTION: kirimNotifikasiAdmin
    // TUJUAN: Mengirim email notifikasi ke semua admin yang aktif & punya email.
    //         Kegagalan kirim email (misal SMTP mati) hanya dicatat ke log,
    //         tidak menggagalkan proses utama (simpan laporan / daftar mitra).
    // ========================================================================
    protected function kirimNotifikasiAdmin(Mailable $mail): void
    {
        $emailAdmin = userModel::where('role', 'Admin')
            ->where('aktif', true)
            ->where('email', '!=', '')
            ->pluck('email')
            ->all();

        if (empty($emailAdmin)) {
            return;
        }

        try {
            Mail::to($emailAdmin)->send($mail);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
