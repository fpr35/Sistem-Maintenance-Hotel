<?php

namespace App\Http\Controllers;

use App\Models\userModel;
use Illuminate\Http\Request;

class kelolaUserController extends Controller
{
    // ========================================================================
    // FUNCTION: index
    // TUJUAN: Menampilkan daftar semua akun, bisa dicari & difilter per role
    // ========================================================================
    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $role = $request->role;

            $users = userModel::query()
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->when(in_array($role, ['Admin', 'Pegawai', 'Tamu', 'Mitra']), function ($query) use ($role) {
                    $query->where('role', $role);
                })
                ->orderBy('role')
                ->orderBy('nama')
                ->paginate(10)
                ->withQueryString();

            return view('admin.page.kelola_user', compact('users'));

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $this->pesanError($e));
        }
    }

    // ========================================================================
    // FUNCTION: ubah_status
    // TUJUAN: Mengaktifkan / menonaktifkan akun.
    // Penjelasan: Akun tidak dihapus karena datanya (laporan, barang,
    //             pembelian) masih dipakai di riwayat. Akun nonaktif
    //             tidak bisa login.
    // ========================================================================
    public function ubah_status($id)
    {
        try {
            $user = userModel::findOrFail($id);

            // Admin tidak boleh menonaktifkan dirinya sendiri / admin lain
            if ($user->role === 'Admin') {
                return redirect()->back()->with('error', 'Akun admin tidak dapat dinonaktifkan.');
            }

            $user->aktif = !$user->aktif;
            $user->save();

            $status = $user->aktif ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()->back()->with('success', "Akun {$user->username} berhasil {$status}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e));
        }
    }
}
