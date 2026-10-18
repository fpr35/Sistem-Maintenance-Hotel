<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAktif
{
    // ========================================================================
    // MIDDLEWARE: aktif
    // TUJUAN: Mengeluarkan user yang akunnya dinonaktifkan admin, walaupun
    //         user tersebut sedang dalam keadaan login.
    // ========================================================================
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->aktif) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun anda telah dinonaktifkan. Silahkan hubungi admin.');
        }

        return $next($request);
    }
}
