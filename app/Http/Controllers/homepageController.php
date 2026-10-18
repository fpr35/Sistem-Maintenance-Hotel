<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\laporan_kerusakanModel;
use Carbon\Carbon;

class homepageController extends Controller
{
    // ========================================================================
    // FUNCTION: index
    // TUJUAN: Menampilkan halaman utama (Dashboard/Homepage) beserta statistik ringkas
    // ========================================================================
    public function index()
    {
        try {
            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil Data Laporan untuk Tabel
            // Penjelasan: 
            // - paginate(10) artinya mengambil semua data laporan tapi dipotong
            //   menjadi 10 baris per halaman (agar website tidak berat loadingnya).
            // ----------------------------------------------------------------
            $laporan_semua = laporan_kerusakanModel::orderBy('laporan_id', 'desc')->paginate(10); // 10 laporan terbaru

            // ----------------------------------------------------------------
            // LANGKAH 2: Hitung Statistik Laporan (Counter)
            // Penjelasan:
            // - where('status_laporan', 'X') -> Cari yang statusnya X.
            // - count() -> Hitung ada berapa jumlahnya (hasilnya angka, misal: 5).
            // ----------------------------------------------------------------
            
            // Hitung laporan yang masih "Menunggu"
            $laporan_masuk = laporan_kerusakanModel::where('status_laporan', 'Menunggu')->count();
            
            // Hitung laporan yang sudah "Diverifikasi" admin
            $laporan_terverifikasi = laporan_kerusakanModel::where('status_laporan', 'Diverifikasi')->count();
            
            // Hitung laporan yang sedang "Diperbaiki"
            $laporan_dikerjakan = laporan_kerusakanModel::where('status_laporan', 'Diperbaiki')->count();

            // ----------------------------------------------------------------
            // LANGKAH 3: Siapkan Filter Tanggal (Bulan Ini)
            // Penjelasan: Kita ingin menghitung laporan selesai HANYA di bulan ini.
            // - now() = Waktu saat ini.
            // - copy() = Duplikat waktu agar variabel asli tidak berubah.
            // - startOfMonth() = Tanggal 1 bulan ini.
            // - endOfMonth() = Tanggal terakhir bulan ini.
            // ----------------------------------------------------------------
            $today = now();
            $firstDayOfMonth = $today->copy()->startOfMonth();
            $lastDayOfMonth = $today->copy()->endOfMonth();

            // ----------------------------------------------------------------
            // LANGKAH 4: Hitung Laporan Selesai (Khusus Bulan Ini)
            // Penjelasan:
            // - whereBetween(...) = Cari data di antara tanggal awal dan akhir bulan ini.
            // - where('status_laporan', 'Selesai') = Dan statusnya harus 'Selesai'.
            // ----------------------------------------------------------------
            $laporan_selesai = laporan_kerusakanModel::whereBetween('tanggal_lapor', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('status_laporan', 'Selesai')
                ->count();

            // ----------------------------------------------------------------
            // LANGKAH 5: Tampilkan View Homepage
            // Penjelasan: compact(...) mengirim semua variabel hitungan tadi ke file tampilan (blade).
            // ----------------------------------------------------------------
            return view('homepage.index', compact('laporan_semua', 'laporan_masuk', 'laporan_terverifikasi', 'laporan_dikerjakan', 'laporan_selesai'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // Penjelasan: Jika terjadi kesalahan saat mengambil data (misal database mati),
            // user tidak akan melihat layar error putih, tapi pesan error yang rapi.
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan memuat halaman'));
        }
    }
}