<?php

namespace App\Http\Controllers;

use App\Models\laporan_kerusakanModel;

class laporanController extends Controller
{
    // ========================================================================
    // FUNCTION: detail
    // TUJUAN: Menampilkan detail lengkap satu laporan kerusakan.
    // AKSES : - Admin & Mitra   → semua laporan
    //         - Tamu & Pegawai  → hanya laporan miliknya sendiri
    // ========================================================================
    public function detail($id)
    {
        try {
            $user = auth()->user();

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil laporan beserta pelapor & pembelian terkait
            // ----------------------------------------------------------------
            $laporan = laporan_kerusakanModel::with(['pelapor', 'pembelian.mitra_bisnis'])->find($id);

            if (!$laporan) {
                return redirect()->route('dashboard')->with('error', 'Laporan tidak ditemukan.');
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Tamu/Pegawai hanya boleh melihat laporannya sendiri
            // ----------------------------------------------------------------
            if (in_array($user->role, ['Tamu', 'Pegawai']) && (int) $laporan->user_id !== (int) $user->user_id) {
                return redirect()->route('laporan_kerusakan')
                    ->with('error', 'Laporan tidak ditemukan atau bukan milik Anda.');
            }

            return view('laporan.detail', compact('laporan', 'user'));

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $this->pesanError($e));
        }
    }
}
