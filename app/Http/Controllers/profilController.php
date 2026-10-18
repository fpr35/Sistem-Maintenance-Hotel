<?php

namespace App\Http\Controllers;

use App\Models\userModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class profilController extends Controller
{
    // ========================================================================
    // FUNCTION: index
    // TUJUAN: Menampilkan halaman profil user yang sedang login (semua role)
    // ========================================================================
    public function index()
    {
        $user = auth()->user();
        $mitra_bisnis = $user->role === 'Mitra' ? $user->mitra_bisnis : null;

        return view('profil.index', compact('user', 'mitra_bisnis'));
    }

    // ========================================================================
    // FUNCTION: update
    // TUJUAN: Menyimpan perubahan data diri (nama, kontak, email).
    //         Untuk Mitra juga alamat & data rekening di tabel mitra_bisnis.
    // ========================================================================
    public function update(Request $request)
    {
        try {
            $user = userModel::findOrFail(auth()->id());
            $isMitra = $user->role === 'Mitra';

            // ----------------------------------------------------------------
            // LANGKAH 1: Validasi input
            // Penjelasan: Rule::unique()->ignore() agar email milik sendiri
            //             tidak dianggap "sudah terdaftar".
            // ----------------------------------------------------------------
            $rules = [
                'nama' => 'required|string|max:100',
                'kontak' => 'required|regex:/^\+?[0-9]{8,15}$/',
                'email' => ['required', 'email', 'max:100', Rule::unique('user', 'email')->ignore($user->user_id, 'user_id')],
            ];

            if ($isMitra) {
                $rules['alamat'] = 'required|string|max:255';
                $rules['nama_bank'] = 'required|string|max:100';
                $rules['no_rekening'] = 'required|digits_between:5,20';
            }

            $validator = Validator::make($request->all(), $rules, [
                'nama.required' => 'Nama harus diisi',
                'kontak.required' => 'Kontak harus diisi',
                'kontak.regex' => 'Kontak harus berupa nomor HP 8-15 digit',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah dipakai akun lain',
                'alamat.required' => 'Alamat harus diisi',
                'nama_bank.required' => 'Nama bank harus diisi',
                'no_rekening.required' => 'Nomor rekening harus diisi',
                'no_rekening.digits_between' => 'Nomor rekening harus 5-20 digit angka',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Simpan data akun
            // ----------------------------------------------------------------
            $user->nama = $request->nama;
            $user->kontak = $request->kontak;
            $user->email = $request->email;
            $user->save();

            // ----------------------------------------------------------------
            // LANGKAH 3: Untuk Mitra, samakan juga data di tabel mitra_bisnis
            // ----------------------------------------------------------------
            if ($isMitra && $user->mitra_bisnis) {
                $mitra = $user->mitra_bisnis;
                $mitra->kontak = $request->kontak;
                $mitra->email = $request->email;
                $mitra->alamat = $request->alamat;
                $mitra->nama_bank = $request->nama_bank;
                $mitra->no_rekening = $request->no_rekening;
                $mitra->save();
            }

            return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e))->withInput();
        }
    }

    // ========================================================================
    // FUNCTION: update_password
    // TUJUAN: Mengganti password. Wajib memasukkan password lama dulu.
    // ========================================================================
    public function update_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password_lama' => 'required',
                'password' => 'required|min:6|confirmed',
            ], [
                'password_lama.required' => 'Password lama harus diisi',
                'password.required' => 'Password baru harus diisi',
                'password.min' => 'Password baru minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password baru tidak sama',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $user = userModel::findOrFail(auth()->id());

            // Pastikan yang mengganti benar-benar pemilik akun
            if (!Hash::check($request->password_lama, $user->password)) {
                return redirect()->back()->withErrors(['password_lama' => 'Password lama salah']);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->route('profil')->with('success', 'Password berhasil diganti.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e));
        }
    }
}
