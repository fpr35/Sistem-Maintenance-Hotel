<?php

namespace App\Http\Controllers;

use App\Models\userModel;
use App\Models\laporan_kerusakanModel;
use App\Models\mitra_bisnisModel;
use App\Models\barang_jasaModel;
use App\Models\pembelianModel;
use App\Models\unverified_akun_mitraModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; 
use App\Mail\ResetPasswordMail;
use App\Mail\NotifikasiAdminMail;
use Illuminate\Support\Facades\Password;

class authController extends Controller
{
    public function form_login()
    {
        return view('auth.login');
    }

    public function form_register()
    {
        return view('auth.register');
    }

    public function form_reset_password()
    {
        return view('auth.reset_password');
    }

    public function reset_password(Request $request)
    {
        try {
            // ============================================================
            // 1. Membuat validator manual untuk menangani validasi input
            // ============================================================
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ],[
                // Pesan error yang lebih spesifik
                'email.required' => 'Email harus diisi',
                'email.email' => 'Email tidak valid',
            ]);

            // ============================================================
            // 2. Jika validasi gagal ⇒ kembali ke halaman sebelumnya
            // ============================================================
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)    // mengirim pesan error ke view
                    ->withInput();              // mengirim data input kembali ke view
            }

            // ============================================================
            // 3. Cek apakah email ada di tabel user
            // ============================================================
            $user = userModel::where('email', $request->email)->first();

            if (!$user) {
                return redirect()->back()
                    ->with('error', 'Email tidak terdaftar')
                    ->withInput();
            }

            $token = Password::createToken($user);
            // $link = url("/reset-password/$token?email={$user->email}");

            // ============================================================
            // 4. Kirim email reset password
            // ============================================================
            Mail::to($user->email)->queue(new ResetPasswordMail($user,$token));

            return redirect()->route('login')
                ->with('success', 'Link reset password sudah dikirim. Silakan cek email Anda.');


        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $this->pesanError($e, 'Terjadi kesalahan'))
                ->withInput();
        }
    }

    public function form_new_password($token,$id)
    {
        $user = userModel::where('user_id', $id)->first();

        // Pastikan user ada dan token reset masih valid (belum kedaluwarsa)
        if (!$user || !Password::tokenExists($user, $token)) {
            return redirect()->route('reset_password')
                ->with('error', 'Link sudah tidak valid, silahkan lakukan reset password lagi');
        }

        return view('auth.new_password', compact('user', 'token'));
    }

    public function new_password(Request $request, $id)
    {
        try {
            // ============================================================
            // 1. Membuat validator manual untuk menangani validasi input
            // ============================================================
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'password' => 'required|min:6|confirmed',
            ],[
                // Pesan error yang lebih spesifik
                'token.required' => 'Token tidak ditemukan',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sama',
            ]);

            // ============================================================
            // 2. Jika validasi gagal ⇒ kembali ke halaman sebelumnya
            // ============================================================
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)    // mengirim pesan error ke view
                    ->withInput();              // mengirim data input kembali ke view
            }
            
            $user = userModel::where('user_id', $id)->first();

            // ============================================================
            // 3. Cocokkan token dengan yang tersimpan di database
            //    → Tanpa ini siapa pun bisa mengganti password user lain
            // ============================================================
            if (!$user || !Password::tokenExists($user, $request->token)) {
                return redirect()->route('reset_password')
                    ->with('error', 'Link sudah tidak valid, silahkan lakukan reset password lagi');
            }

            $user->password = Hash::make($request->password);
            $user->save();

            // Hapus token agar link reset tidak bisa dipakai ulang
            Password::deleteToken($user);
            
            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah');
        
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $this->pesanError($e, 'Terjadi kesalahan'))
                ->withInput();
        }
    }

    public function register(Request $request)
    {
        try {
            // ============================================================
            // 1. Membuat validator manual untuk menangani validasi input
            // ============================================================
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required|unique:user',
                'password' => 'required|min:6|confirmed',
                'role' => 'required|in:Pegawai,Tamu', // hanya role ini yang boleh daftar sendiri
                'kontak' => 'required|regex:/^\+?[0-9]{8,15}$/', // 8-15 digit, boleh diawali +
                'email' => 'required|email|unique:user',
            ],[
                // Pesan error yang lebih spesifik
                'nama.required' => 'Nama harus diisi',
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah terdaftar',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sama',
                'role.required' => 'Role harus diisi',
                'role.in' => 'Role tidak valid',
                'kontak.required' => 'Kontak harus diisi',
                'kontak.regex' => 'Kontak harus berupa nomor HP 8-15 digit',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
            ]);

            // ============================================================
            // 2. Jika validasi gagal ⇒ kembali ke halaman sebelumnya
            // ============================================================
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)    // mengirim pesan error ke view
                    ->withInput();              // mengisi ulang data input
            }

            // ============================================================
            // 3. Ambil data valid yang sudah lolos validasi
            // ============================================================
            $validated = $validator->validated();

            // ============================================================
            // 4. Simpan data ke database
            // ============================================================
            $user = new userModel();
            $user->nama = $validated['nama'];
            $user->username = $validated['username'];
            $user->password = Hash::make($validated['password']); // enkripsi password
            $user->role = $validated['role'];
            $user->kontak = $validated['kontak'];
            $user->email = $validated['email'];
            $user->save();   // simpan ke tabel database

            // ============================================================
            // 5. Jika berhasil ⇒ redirect ke halaman login dengan pesan sukses
            // ============================================================
            return redirect()
                ->route('login')
                ->with('success', 'Pendaftaran berhasil. Silakan login.');

        } catch (\Exception $e) {

            // ============================================================
            // 6. Jika terjadi error lain (misalnya error query database)
            // ============================================================

            return redirect()->back()
                ->with('error', $this->pesanError($e, 'Terjadi kesalahan pada server'))
                ->withInput();
        }
    }

    public function form_register_mitra()
    {
        return view('auth.register_mitra');
    }

    public function register_mitra(Request $request)
    {
        try {
            // ============================================================
            // 1. Membuat validator manual untuk memeriksa semua input
            // ============================================================
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'username' => 'required|unique:user|unique:unverified_akun_mitra',
                'email' => 'required|email|unique:mitra_bisnis|unique:unverified_akun_mitra',
                'password' => 'required|min:6|confirmed',
                'alamat' => 'required',
                'kontak' => 'required|regex:/^\+?[0-9]{8,15}$/', // 8-15 digit, boleh diawali +
                'nama_bank' => 'required',
                'no_rekening' => 'required|digits_between:5,20',
                'kategori' => 'required|in:Vendor Barang,Vendor Jasa',
            ], [
                // Pesan error validasi khusus
                'nama.required' => 'Nama harus diisi',
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah terdaftar',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sama',
                'kontak.required' => 'Kontak harus diisi',
                'kontak.regex' => 'Kontak harus berupa nomor HP 8-15 digit',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'nama_bank.required' => 'Nama Bank harus diisi',
                'no_rekening.required' => 'Nomor Rekening harus diisi',
                'no_rekening.digits_between' => 'Nomor Rekening harus 5-20 digit angka',
                'kategori.required' => 'Kategori harus dipilih',
                'kategori.in' => 'Kategori tidak valid',
            ]);

            // ============================================================
            // 2. Jika validasi gagal → kembali ke halaman form
            // ============================================================
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)    // kirim pesan error ke view
                    ->withInput();              // isi ulang form dengan input sebelumnya
            }

            // ============================================================
            // 3. Ambil input yang sudah tervalidasi
            // ============================================================
            $validated = $validator->validated();

            // ============================================================
            // 4. Simpan data ke tabel unverified_akun_mitra
            // ============================================================
            $mitra = new unverified_akun_mitraModel();
            $mitra->nama = $validated['nama'];
            $mitra->username = $validated['username'];
            $mitra->email = $validated['email'];
            $mitra->password = Hash::make($validated['password']); // jangan simpan password mentah
            $mitra->alamat = $validated['alamat'];
            $mitra->kontak = $validated['kontak'];
            $mitra->nama_bank = $validated['nama_bank'];
            $mitra->no_rekening = $validated['no_rekening'];
            $mitra->kategori = $validated['kategori'];
            $mitra->save();    // simpan ke database

            // Beri tahu admin bahwa ada mitra baru yang perlu diverifikasi
            $this->kirimNotifikasiAdmin(new NotifikasiAdminMail(
                'Pendaftaran Mitra Baru',
                'Ada calon mitra baru yang menunggu verifikasi akun.',
                [
                    'Nama Mitra' => $mitra->nama,
                    'Kategori' => $mitra->kategori,
                    'Email' => $mitra->email,
                    'Kontak' => $mitra->kontak,
                ],
                route('mitra_belum_terverifikasi'),
                'Verifikasi Sekarang'
            ));

            // ============================================================
            // 5. Jika berhasil → redirect ke login dengan pesan sukses
            // ============================================================
            return redirect()->route('login')
                ->with('success', 'Pendaftaran mitra berhasil. Silakan tunggu verifikasi admin. Anda akan mendapatkan pemberitahuan melalui Email');

        } catch (\Exception $e) {

            // ============================================================
            // 6. Jika terjadi error lain (misal database error)
            // ============================================================
            return redirect()->back()
                ->with('error', $this->pesanError($e, 'Terjadi kesalahan pada server'))
                ->withInput();
        }
    }

    public function login(Request $request)
    {
        try {
            // ============================================================
            // 1. Validasi input login (username dan password wajib diisi)
            // ============================================================
            $validated = $request->validate([
                'username' => 'required',
                'password' => 'required',
            ], [
                'username.required' => 'Username harus diisi',
                'password.required' => 'Password harus diisi',
            ]);

            // ============================================================
            // 2. Cek apakah username ada di database (opsional)
            //    Bisa digunakan jika ingin menampilkan pesan khusus
            // ============================================================
            $user = userModel::where('username', $validated['username'])->first();

            // ============================================================
            // 3. Attempt login menggunakan Auth::attempt()
            //    → Mengembalikan true jika username & password cocok
            // ============================================================
            if (auth()->attempt($validated)) {

                // Akun yang dinonaktifkan admin tidak boleh masuk
                if (!auth()->user()->aktif) {
                    auth()->logout();
                    return redirect()->back()
                        ->with('error', 'Akun anda telah dinonaktifkan. Silahkan hubungi admin.')
                        ->withInput($request->only('username'));
                }

                // Regenerate session ID untuk keamanan
                $request->session()->regenerate();

                // Redirect ke dashboard setelah login berhasil
                return redirect()->intended('/dashboard');
            } else {

                // Jika gagal login, kembali dengan pesan error
                return redirect()->back()->with('error', 'Username atau Password anda salah');
            }

        } catch (\Exception $e) {

            // ============================================================
            // 4. Jika terjadi error server (database, authentikasi, dll)
            // ============================================================
            return redirect()->back()
                ->with('error', $this->pesanError($e, 'Terjadi kesalahan pada server'))
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->logout(); // 1. Logout user dari sistem
            $request->session()->invalidate(); // 2. Invalidate session agar sesi lama tidak bisa digunakan lagi
            $request->session()->regenerateToken(); // 3. Regenerate CSRF Token untuk keamanan
            return redirect('/login'); // 4. Redirect ke halaman login setelah logout
        } catch (\Exception $e) {
            // Jika ada error saat logout/session
            return redirect()->back()
                ->with('error', $this->pesanError($e, 'Terjadi kesalahan saat logout'));
        }
    }

    public function dashboard()
    {
        try {
            $role = auth()->user()->role; // 1. Ambil role user yang sedang login
            
            switch ($role) { // 2. Arahkan dashboard berdasarkan role user

                // --------------------------------------------------------
                // ROLE ADMIN
                // --------------------------------------------------------
                case 'Admin':
                    $nama = auth()->user()->nama;

                    // Mengambil data laporan, pembelian, barang, mitra belum diverifikasi
                    $laporan_all = laporan_kerusakanModel::orderBy('laporan_id', 'desc')->paginate(5);
                    $pembelian_all = pembelianModel::orderBy('pembelian_id', 'desc')->paginate(5);
                    $barang_jasa = barang_jasaModel::with('mitra')->orderBy('barang_id', 'desc')->paginate(5);
                    $mitra_belum_verifikasi_all = unverified_akun_mitraModel::orderBy('created_at', 'desc')->paginate(5);

                    return view('admin.dashboard', compact(
                        'laporan_all',
                        'pembelian_all',
                        'barang_jasa',
                        'mitra_belum_verifikasi_all',
                        'nama'
                    ));

                // --------------------------------------------------------
                // ROLE PEGAWAI atau TAMU
                // --------------------------------------------------------
                case 'Pegawai':
                case 'Tamu':

                    $nama = auth()->user()->nama;

                    $laporan_masuk = laporan_kerusakanModel::where('status_laporan', 'Menunggu')->count();
                    $laporan_terverifikasi = laporan_kerusakanModel::where('status_laporan', 'Diverifikasi')->count();
                    $laporan_dikerjakan = laporan_kerusakanModel::where('status_laporan', 'Diperbaiki')->count();

                    // Periode tanggal bulan ini
                    $firstDayOfMonth = Carbon::now()->startOfMonth()->toDateString();
                    $lastDayOfMonth = Carbon::now()->endOfMonth()->toDateString();

                    $laporan_selesai = laporan_kerusakanModel::whereBetween('tanggal_lapor', [$firstDayOfMonth, $lastDayOfMonth])
                        ->where('status_laporan', 'Selesai')
                        ->count();

                    return view('pegawai-tamu.dashboard', compact(
                        'laporan_masuk',
                        'laporan_terverifikasi',
                        'laporan_dikerjakan',
                        'laporan_selesai',
                        'nama'
                    ));

                // --------------------------------------------------------
                // ROLE MITRA
                // --------------------------------------------------------
                case 'Mitra':

                    $nama = auth()->user()->nama;

                    // Semua laporan kerusakan
                    $laporan_all = laporan_kerusakanModel::orderBy('laporan_id', 'desc')->paginate(5);

                    // Ambil data mitra terkait user login
                    $mitra_bisnis = mitra_bisnisModel::where('user_id', auth()->id())->first();

                    // Akun mitra tanpa data mitra_bisnis tidak bisa dipakai
                    if (!$mitra_bisnis) {
                        auth()->logout();
                        return redirect()->route('login')
                            ->with('error', 'Data mitra untuk akun ini tidak ditemukan. Silahkan hubungi admin.');
                    }

                    // Barang dan pembelian yang terkait mitra
                    $barang_jasa = barang_jasaModel::where('mitra_id', $mitra_bisnis->mitra_id)
                        ->orderBy('barang_id', 'desc')
                        ->paginate(5);

                    $pembelian_all = pembelianModel::where('mitra_id', $mitra_bisnis->mitra_id)
                        ->orderBy('pembelian_id', 'desc')
                        ->paginate(5);

                    return view('mitra.dashboard', compact(
                        'laporan_all',
                        'barang_jasa',
                        'pembelian_all',
                        'nama'
                    ));

                // --------------------------------------------------------
                // ROLE TIDAK DIKENALI
                // --------------------------------------------------------
                default:
                    return redirect()->route('login');
            }

        } catch (\Exception $e) {

            // ============================================================
            // Jika ada error server/database pada dashboard
            // ============================================================
            return redirect()->route('login')
                ->with('error', $this->pesanError($e, 'Terjadi kesalahan saat membuka dashboard'));
        }
    }

}
