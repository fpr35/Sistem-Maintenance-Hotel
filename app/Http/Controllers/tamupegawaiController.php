<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\laporan_kerusakanModel;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; 
use App\Mail\NotifikasiAdminMail;

class tamupegawaiController extends Controller
{
    // ========================================================================
    // FUNCTION: cariLaporanMilikUser
    // TUJUAN: Mengambil laporan HANYA jika laporan itu milik user yang login.
    //         Mencegah user mengubah/menghapus laporan orang lain lewat URL.
    // ========================================================================
    private function cariLaporanMilikUser($id)
    {
        return laporan_kerusakanModel::where('user_id', auth()->id())->find($id);
    }

    // ========================================================================
    // FUNCTION: tolakAksesLaporan
    // TUJUAN: Mengembalikan redirect jika laporan tidak boleh diubah/dihapus,
    //         atau null jika boleh.
    // ========================================================================
    private function tolakAksesLaporan($laporan)
    {
        if (!$laporan) {
            return redirect()->route('laporan_kerusakan')
                ->with('error', 'Laporan tidak ditemukan atau bukan milik Anda.');
        }

        // Laporan yang sudah diproses admin tidak boleh diubah/dihapus pelapor
        if ($laporan->status_laporan !== 'Menunggu') {
            return redirect()->route('laporan_kerusakan')
                ->with('error', 'Laporan yang sudah diproses tidak dapat diubah atau dihapus.');
        }

        return null;
    }

    // ========================================================================
    // FUNCTION: laporan_kerusakan
    // TUJUAN: Menampilkan daftar laporan kerusakan yang pernah dibuat oleh user (history)
    // ========================================================================
    public function laporan_kerusakan()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil Data Laporan Milik User Sendiri
            // ----------------------------------------------------------------
            $laporan_riwayat = laporan_kerusakanModel::where('user_id', auth()->id())->paginate(10);

            // ----------------------------------------------------------------
            // LANGKAH 2: Tampilkan Halaman
            // ----------------------------------------------------------------
            return view('pegawai-tamu.page.laporan_kerusakan', compact('laporan_riwayat'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: tambah
    // TUJUAN: Menampilkan FORM untuk membuat laporan baru
    // ========================================================================
    public function tambah()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Tampilkan Halaman Form
            // ----------------------------------------------------------------
            return view('pegawai-tamu.form.tambah');

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: simpan
    // TUJUAN: Memproses data dari form tambah dan menyimpannya ke database
    // ========================================================================
    public function simpan(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Validasi Inputan User 
            // Penjelasan: 
            // - Validator::make() digunakan untuk membuat aturan pengecekan.
            // - Array pertama: Aturan (Rules).
            // - Array kedua: Pesan Error Kustom 
            // ----------------------------------------------------------------
            $validator = Validator::make($request->all(), [
                'tanggal_lapor' => 'required|date|before_or_equal:today', // tidak boleh tanggal masa depan
                'lokasi_fasilitas' => 'required|string|max:150',
                'deskripsi_kerusakan' => 'required',
                'status_laporan' => 'nullable|string|max:255',
                'foto_kerusakan' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ], [
                // Pesan error jika user salah input / kosong
                'tanggal_lapor.required' => 'Tanggal laporan harus diisi',
                'tanggal_lapor.date' => 'Format tanggal tidak valid',
                'tanggal_lapor.before_or_equal' => 'Tanggal laporan tidak boleh melebihi hari ini',
                'lokasi_fasilitas.required' => 'Lokasi fasilitas harus diisi',
                'lokasi_fasilitas.max' => 'Lokasi fasilitas maksimal 150 karakter',
                'deskripsi_kerusakan.required' => 'Deskripsi kerusakan harus diisi',
                'foto_kerusakan.required' => 'Foto kerusakan wajib diupload',
                'foto_kerusakan.image' => 'File harus berupa gambar',
                'foto_kerusakan.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
                'foto_kerusakan.max' => 'Ukuran gambar maksimal 5 MB',
            ]);

            // ----------------------------------------------------------------
            // LANGKAH 2: Cek Hasil Validasi
            // Penjelasan: Jika validasi gagal (fails), kembalikan user ke form
            // bawa pesan errornya (withErrors) dan isian sebelumnya (withInput).
            // ----------------------------------------------------------------
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ----------------------------------------------------------------
            // LANGKAH 3: Proses Upload dan Kompresi Gambar
            // ----------------------------------------------------------------
            $file = $request->file('foto_kerusakan');

            $manager = new ImageManager(new Driver());

            $image = $manager->read($file)->toJpeg(30); // compress ke JPG kualitas 30%

            $filename = uniqid().'.jpg';

            // Simpan file hasil kompresi ke folder public/foto_kerusakan
            Storage::disk('public')->put("foto_kerusakan/{$filename}", $image);

            // ----------------------------------------------------------------
            // LANGKAH 4: Simpan Data ke Database
            // ----------------------------------------------------------------
            $laporan = new laporan_kerusakanModel();
            $laporan->tanggal_lapor = $request->tanggal_lapor;
            $laporan->lokasi_fasilitas = $request->lokasi_fasilitas;
            $laporan->deskripsi_kerusakan = $request->deskripsi_kerusakan;
            $laporan->status_laporan = 'Menunggu'; // Laporan baru selalu berstatus Menunggu
            $laporan->user_id = auth()->id(); // Otomatis isi ID user yang login
            $laporan->foto_kerusakan = "foto_kerusakan/{$filename}";
            $laporan->save();

            // Beri tahu admin bahwa ada laporan kerusakan baru
            $this->kirimNotifikasiAdmin(new NotifikasiAdminMail(
                'Laporan Kerusakan Baru',
                'Ada laporan kerusakan baru yang menunggu verifikasi.',
                [
                    'Pelapor' => auth()->user()->nama . ' (' . auth()->user()->role . ')',
                    'Lokasi' => $laporan->lokasi_fasilitas,
                    'Tanggal' => \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d M Y'),
                    'Deskripsi' => $laporan->deskripsi_kerusakan,
                ],
                route('detail_laporan', $laporan->laporan_id),
                'Lihat Laporan'
            ));

            // ----------------------------------------------------------------
            // LANGKAH 5: Redirect Sukses
            // ----------------------------------------------------------------
            return redirect()->route('dashboard')->with('success', 'Laporan berhasil ditambahkan');

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: edit
    // TUJUAN: Menampilkan form edit laporan
    // ========================================================================
    public function edit($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Cari Data Laporan
            // ----------------------------------------------------------------
            $laporan = $this->cariLaporanMilikUser($id);
            if ($tolak = $this->tolakAksesLaporan($laporan)) {
                return $tolak;
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Tampilkan View Edit
            // ----------------------------------------------------------------
            return view('pegawai-tamu.form.edit', compact('laporan'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: update
    // TUJUAN: Memproses perubahan data (edit) dan menyimpannya kembali
    // ========================================================================
    public function update(Request $request, $id){
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Validasi Input Baru (Gaya Admin Controller)
            // Penjelasan: Foto tidak wajib ('required' dihapus) di sini.
            // ----------------------------------------------------------------
            $validator = Validator::make($request->all(), [
                'tanggal_lapor' => 'required|date|before_or_equal:today', // tidak boleh tanggal masa depan
                'lokasi_fasilitas' => 'required|string|max:150',
                'deskripsi_kerusakan' => 'required',
                'status_laporan' => 'nullable|string|max:255',
                'foto_kerusakan' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // Tidak pakai required
            ], [
                'tanggal_lapor.required' => 'Tanggal laporan harus diisi',
                'tanggal_lapor.before_or_equal' => 'Tanggal laporan tidak boleh melebihi hari ini',
                'lokasi_fasilitas.required' => 'Lokasi fasilitas harus diisi',
                'deskripsi_kerusakan.required' => 'Deskripsi kerusakan harus diisi',
                'foto_kerusakan.image' => 'File harus berupa gambar',
                'foto_kerusakan.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
                'foto_kerusakan.max' => 'Ukuran gambar maksimal 5 MB',
            ]);

            // ----------------------------------------------------------------
            // LANGKAH 2: Cek Hasil Validasi
            // ----------------------------------------------------------------
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ----------------------------------------------------------------
            // LANGKAH 3: Ambil Data Lama
            // ----------------------------------------------------------------
            $laporan = $this->cariLaporanMilikUser($id);
            if ($tolak = $this->tolakAksesLaporan($laporan)) {
                return $tolak;
            }

            // ----------------------------------------------------------------
            // LANGKAH 4: Cek Apakah Ada Upload Foto Baru?
            // ----------------------------------------------------------------
            if ($request->hasFile('foto_kerusakan')) {
                // Hapus foto lama
                Storage::disk('public')->delete($laporan->foto_kerusakan);
                
                // Proses foto baru
                $file = $request->file('foto_kerusakan');
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file)->toJpeg(30);
                $filename = uniqid().'.jpg';
                
                // simpan ke storage public
                Storage::disk('public')->put("foto_kerusakan/{$filename}", $image);
                
                // Update nama file di database
                $laporan->foto_kerusakan = "foto_kerusakan/{$filename}";
            } 

            // ----------------------------------------------------------------
            // LANGKAH 5: Simpan Perubahan Data Teks
            // ----------------------------------------------------------------
            $laporan->tanggal_lapor = $request->tanggal_lapor;
            $laporan->lokasi_fasilitas = $request->lokasi_fasilitas;
            $laporan->deskripsi_kerusakan = $request->deskripsi_kerusakan;
            // status_laporan & user_id tidak diubah: status hanya boleh diubah admin
            $laporan->save();

            // ----------------------------------------------------------------
            // LANGKAH 6: Redirect Sukses
            // ----------------------------------------------------------------
            return redirect()->route('dashboard')->with('success', 'Laporan berhasil diperbarui.');

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: hapus
    // TUJUAN: Menghapus data laporan dari database
    // ========================================================================
    public function hapus($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Cari dan Hapus Data
            // ----------------------------------------------------------------
            $laporan = $this->cariLaporanMilikUser($id);
            if ($tolak = $this->tolakAksesLaporan($laporan)) {
                return $tolak;
            }

            $laporan->delete();

            // Hapus juga file foto kerusakan dari storage
            if ($laporan->foto_kerusakan) {
                Storage::disk('public')->delete($laporan->foto_kerusakan);
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Redirect Sukses
            // ----------------------------------------------------------------
            return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus');

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: pencarian_laporan_kerusakan
    // TUJUAN: Mencari laporan berdasarkan kata kunci yang diketik user
    // ========================================================================
    public function pencarian_laporan_kerusakan(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil Kata Kunci Pencarian
            // ----------------------------------------------------------------
            $search = $request->search;

            // ----------------------------------------------------------------
            // LANGKAH 2: Lakukan Pencarian di Database
            // ----------------------------------------------------------------
            // Kondisi OR dikelompokkan dalam closure agar filter user_id tetap
            // berlaku (tanpa ini laporan milik user lain ikut muncul)
            $laporan_riwayat = laporan_kerusakanModel::where('user_id', auth()->id())
            ->where(function ($query) use ($search) {
                $query->where('lokasi_fasilitas', 'like', "%{$search}%")
                ->orWhere('tanggal_lapor', 'like', "%{$search}%")
                ->orWhere('deskripsi_kerusakan', 'like', "%{$search}%")
                ->orWhere('status_laporan', 'like', "%{$search}%");
            })
            ->paginate(10)->withQueryString();

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan Hasil Pencarian
            // ----------------------------------------------------------------
            return view('pegawai-tamu.page.laporan_kerusakan', compact('laporan_riwayat'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }
}