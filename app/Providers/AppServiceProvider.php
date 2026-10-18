<?php

namespace App\Providers;

use App\Models\laporan_kerusakanModel;
use App\Models\unverified_akun_mitraModel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ====================================================================
        // Badge notifikasi admin di sidebar & menu mobile:
        //   $notif['laporan'] = laporan baru yang masih "Menunggu"
        //   $notif['mitra']   = pendaftaran mitra yang belum diverifikasi
        // ====================================================================
        View::composer(['snippet.sidebar', 'snippet.header'], function ($view) {
            $notif = ['laporan' => 0, 'mitra' => 0];

            if (auth()->check() && auth()->user()->role === 'Admin') {
                $notif['laporan'] = laporan_kerusakanModel::where('status_laporan', 'Menunggu')->count();
                $notif['mitra'] = unverified_akun_mitraModel::count();
            }

            $view->with('notif', $notif);
        });
    }
}
