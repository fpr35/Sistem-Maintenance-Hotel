<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\tamupegawaiController;
use App\Http\Controllers\mitraController;
use App\Http\Controllers\homepageController;
use App\Http\Controllers\profilController;
use App\Http\Controllers\laporanController;
use App\Http\Controllers\kelolaUserController;
use App\Http\Controllers\rekapController;

Route::get('/', [homepageController::class, 'index'])->name('homepage');

Route::get('/login', [authController::class, 'form_login'])->name('login');
Route::post('/loginProcess', [authController::class, 'login'])->name('loginProcess')->middleware('throttle:5,1');
Route::get('/register', [authController::class, 'form_register'])->name('register');
Route::post('/registerProcess', [authController::class, 'register'])->name('registerProcess');
Route::get('/register_mitra', [authController::class, 'form_register_mitra'])->name('form_register_mitra');
Route::post('/registerProcess_mitra', [authController::class, 'register_mitra'])->name('register_mitra');
Route::post('/logout', [authController::class, 'logout'])->name('logout');
Route::get('/reset_password', [authController::class, 'form_reset_password'])->name('reset_password');
Route::post('/resetPasswordProcess', [authController::class, 'reset_password'])->name('resetPasswordProcess')->middleware('throttle:3,1');
Route::get('/new_password/{token}/{id}', [authController::class, 'form_new_password'])->name('new_password');
Route::post('/new_password_process/{id}', [authController::class, 'new_password'])->name('new_password_process')->middleware('throttle:5,1');


Route::middleware(['auth', 'aktif'])->group(function () {
    Route::get('/dashboard', [authController::class, 'dashboard'])->name('dashboard');

    // Halaman yang bisa diakses semua role
    Route::get('/profil', [profilController::class, 'index'])->name('profil');
    Route::put('/profil', [profilController::class, 'update'])->name('profil_update');
    Route::put('/profil/password', [profilController::class, 'update_password'])->name('profil_password');
    Route::get('/detail_laporan/{id}', [laporanController::class, 'detail'])->name('detail_laporan');

    // Hak akses per role dicek oleh middleware 'role' (app/Http/Middleware/CheckRole.php)
    Route::middleware('role:Tamu,Pegawai')->group(function () {
        Route::get('/laporan_kerusakan', [tamupegawaiController::class, 'laporan_kerusakan'])->name('laporan_kerusakan');
        Route::get('/tambah_laporan', [tamupegawaiController::class, 'tambah'])->name('tambah_laporan');
        Route::post('/simpan_laporan', [tamupegawaiController::class, 'simpan'])->name('simpan_laporan');
        Route::get('/edit_laporan/{id}', [tamupegawaiController::class, 'edit'])->name('edit_laporan');
        Route::put('/update_laporan/{id}', [tamupegawaiController::class, 'update'])->name('update_laporan');
        Route::delete('/hapus_laporan/{id}', [tamupegawaiController::class, 'hapus'])->name('hapus_laporan');
        Route::get('/pencarian_laporan_kerusakan', [tamupegawaiController::class, 'pencarian_laporan_kerusakan'])->name('pencarian_laporan_kerusakan');
    });

    Route::middleware('role:Mitra')->group(function () {
        Route::get('/orderan', [mitraController::class, 'orderan'])->name('orderan');
        Route::get('/histori_orderan', [mitraController::class, 'histori_orderan'])->name('histori_orderan');
        Route::get('/barang_jasa', [mitraController::class, 'barang_jasa'])->name('barang_jasa');
        Route::get('/laporan_kerusakan_mitra', [mitraController::class, 'laporan_kerusakan'])->name('laporan_kerusakan_mitra');
        Route::get('/tambah_barangjasa', [mitraController::class, 'tambah'])->name('tambah_barangjasa');
        Route::post('/simpan_barangjasa', [mitraController::class, 'simpan'])->name('simpan_barangjasa');
        Route::get('/edit_barangjasa/{id}', [mitraController::class, 'edit'])->name('edit_barangjasa');
        Route::put('/update_barangjasa/{id}', [mitraController::class, 'update'])->name('update_barangjasa');
        Route::delete('/hapus_barangjasa/{id}', [mitraController::class, 'hapus'])->name('hapus_barangjasa');
        Route::put('/ubah_status_pembelian/{id}', [mitraController::class, 'ubah_status_pembelian'])->name('ubah_status_pembelian');
        Route::get('/print/resi/{id}', [mitraController::class, 'resiPdf'])->name('resiPdf');
        Route::get('/pencarian_barangjasa_mitra', [mitraController::class, 'pencarian_barangjasa_mitra'])->name('pencarian_barangjasa_mitra');
        Route::get('/pencarian_laporan_kerusakan_mitra', [mitraController::class, 'pencarian_laporan_kerusakan_mitra'])->name('pencarian_laporan_kerusakan_mitra');
        Route::get('/pencarian_orderan', [mitraController::class, 'pencarian_orderan'])->name('pencarian_orderan');
        Route::get('/pencarian_histori_orderan', [mitraController::class, 'pencarian_histori_orderan'])->name('pencarian_histori_orderan');
    });

    Route::middleware('role:Admin')->group(function () {
        Route::get('/kelola_user', [kelolaUserController::class, 'index'])->name('kelola_user');
        Route::put('/kelola_user/{id}/status', [kelolaUserController::class, 'ubah_status'])->name('ubah_status_user');
        Route::get('/rekap/laporan', [rekapController::class, 'laporan'])->name('rekap_laporan');
        Route::get('/rekap/pembelian', [rekapController::class, 'pembelian'])->name('rekap_pembelian');
        Route::get('/laporan_kerusakan_admin', [adminController::class, 'laporan_kerusakan_admin'])->name('laporan_kerusakan_admin');
        Route::get('/list_barang_jasa', [adminController::class, 'list_barang_jasa'])->name('list_barang_jasa');
        Route::get('/mitra_belum_terverifikasi', [adminController::class, 'mitra_belum_terverifikasi'])->name('mitra_belum_terverifikasi');
        Route::get('/riwayat_pembelian', [adminController::class, 'riwayat_pembelian'])->name('riwayat_pembelian');
        Route::put('/ubah_status_laporan/{id}', [adminController::class, 'ubah_status_laporan'])->name('ubah_status_laporan');
        Route::get('/isi_data_resi/{id}', [adminController::class, 'isi_data_resi'])->name('isi_data_resi');
        Route::post('/simpan_resi', [adminController::class, 'simpan_resi'])->name('simpan_resi');
        Route::delete('/batal_pembelian/{id}', [adminController::class, 'batal_pembelian'])->name('batal_pembelian');
        Route::post('/beli_barangjasa', [adminController::class, 'beli_barangjasa'])->name('beli_barangjasa');
        Route::delete('/hapus_barangjasa_admin/{id}', [adminController::class, 'hapus_barangjasa_admin'])->name('hapus_barangjasa_admin');
        Route::post('/verifikasi_akun/{id}', [adminController::class, 'verifikasi_akun'])->name('verifikasi_akun');
        Route::delete('/hapus_unverified_mitra/{id}', [adminController::class, 'hapus_unverified_mitra'])->name('hapus_unverified_mitra');
        Route::get('/pencarian_laporan', [adminController::class, 'pencarian_laporan'])->name('pencarian_laporan');
        Route::get('/pencarian_barangjasa', [adminController::class, 'pencarian_barangjasa'])->name('pencarian_barangjasa');
        Route::get('/pencarian_mitra_belum_verifikasi', [adminController::class, 'pencarian_mitra_belum_verifikasi'])->name('pencarian_mitra_belum_verifikasi');
        Route::get('/pencarian_riwayat_pembelian', [adminController::class, 'pencarian_riwayat_pembelian'])->name('pencarian_riwayat_pembelian');
    });
});
