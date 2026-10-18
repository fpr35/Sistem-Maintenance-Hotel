<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // ========================================================================
    // MIDDLEWARE: role
    // TUJUAN: Membatasi akses route berdasarkan role user yang login.
    // CONTOH: ->middleware('role:Admin') atau ->middleware('role:Tamu,Pegawai')
    // Penjelasan: Pengecekan dilakukan di satu tempat ini, sehingga tidak perlu
    //             ditulis ulang di setiap fungsi controller.
    // ========================================================================
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check() || !in_array(auth()->user()->role, $roles, true)) {
            return redirect()
                ->route('login')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut. Silahkan login sebagai ' . implode('/', $roles) . ' untuk mengaksesnya');
        }

        return $next($request);
    }
}
