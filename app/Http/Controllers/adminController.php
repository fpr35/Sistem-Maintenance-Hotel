<?php

namespace App\Http\Controllers;
use App\Models\laporan_kerusakanModel;
use App\Models\pembelianModel;
use App\Models\barang_jasaModel;
use App\Models\mitra_bisnisModel;
use App\Models\userModel;
use App\Models\unverified_akun_mitraModel;
use App\Models\resiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResiMail;
use App\Mail\VerifikasiAkunMitraMail;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class adminController extends Controller
{
    // ========================================================================
    // FUNCTION: laporanUntukPembelian
    // TUJUAN: Daftar laporan yang sedang ditangani (sudah diverifikasi /
    //         sedang diperbaiki) untuk dipilih saat admin membeli barang/jasa.
    // ========================================================================
    private function laporanUntukPembelian()
    {
        return laporan_kerusakanModel::whereIn('status_laporan', ['Diverifikasi', 'Diperbaiki'])
            ->orderBy('tanggal_lapor', 'desc')
            ->get(['laporan_id', 'lokasi_fasilitas', 'tanggal_lapor']);
    }

    // ========================================================================
    // FUNCTION: laporan_kerusakan_admin
    // TUJUAN: Menampilkan halaman daftar laporan kerusakan untuk admin
    // ========================================================================
    public function laporan_kerusakan_admin()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil data user yang sedang login untuk ditampilkan
            // ----------------------------------------------------------------
            $user = auth()->user();
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil semua data laporan kerusakan dari database
            // Penjelasan: paginate(10) artinya ambil data dan pisahkan per 10 data
            //             Jadi di halaman 1 ada 10 data, halaman 2 ada 10 data, dst
            // ----------------------------------------------------------------
            $laporan_all = laporan_kerusakanModel::with('pelapor')->withCount('pembelian')->orderBy('created_at', 'desc')->paginate(6);

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan halaman view dengan data yang sudah disiapkan
            // Penjelasan: compact() mengirimkan variabel ke halaman view
            //             'user' dan 'laporan_all' bisa digunakan di file blade
            // ----------------------------------------------------------------
            return view('admin.page.laporan_kerusakan', compact('user', 'laporan_all'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR: Jika terjadi kesalahan di proses manapun
            // ----------------------------------------------------------------
            // Kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: list_barang_jasa
    // TUJUAN: Menampilkan halaman daftar barang dan jasa untuk admin
    // ========================================================================
    public function list_barang_jasa()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil data user yang login
            // ----------------------------------------------------------------
            $user = auth()->user();
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil data semua barang dan jasa dari database
            // Penjelasan: Data dipisah per 10 item per halaman
            // ----------------------------------------------------------------
            $barang_jasa = barang_jasaModel::with('mitra')->orderBy('created_at', 'desc')->paginate(6);
            $laporan_aktif = $this->laporanUntukPembelian();

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan halaman dengan data barang/jasa
            // ----------------------------------------------------------------
            return view('admin.page.list_barang_jasa', compact('user', 'barang_jasa', 'laporan_aktif'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: mitra_belum_terverifikasi
    // TUJUAN: Menampilkan daftar mitra bisnis yang belum diverifikasi admin
    // ========================================================================
    public function mitra_belum_terverifikasi()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil data user
            // ----------------------------------------------------------------
            $user = auth()->user();
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil data mitra yang belum terverifikasi
            // Penjelasan: unverified_akun_mitraModel adalah tabel khusus
            //             untuk menyimpan data mitra yang belum diverifikasi
            // ----------------------------------------------------------------
            $mitra_belum_verifikasi_all = unverified_akun_mitraModel::orderBy('created_at', 'desc')->paginate(10);

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan halaman
            // ----------------------------------------------------------------
            return view('admin.page.mitra_belum_terverifikasi', compact('user', 'mitra_belum_verifikasi_all'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: riwayat_pembelian
    // TUJUAN: Menampilkan riwayat semua pembelian yang terjadi
    // ========================================================================
    public function riwayat_pembelian()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil data user
            // ----------------------------------------------------------------
            $user = auth()->user();
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil data pembelian, diurutkan dari yang terbaru
            // Penjelasan: orderBy('pembelian_id', 'desc') artinya:
            //             - Urutkan berdasarkan kolom pembelian_id
            //             - 'desc' = descending = dari besar ke kecil (terbaru dulu)
            //             - paginate(5) = tampilkan 5 data per halaman
            // ----------------------------------------------------------------
            $pembelian_all = pembelianModel::with('laporan')->orderBy('pembelian_id', 'desc')->paginate(5);

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan halaman
            // ----------------------------------------------------------------
            return view('admin.page.riwayat_pembelian', compact('user', 'pembelian_all'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: isi_data_resi
    // TUJUAN: Menampilkan form untuk mengisi data resi pengiriman
    // PARAMETER: $id = ID pembelian yang akan dibuatkan resinya
    // ========================================================================
    public function isi_data_resi($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil data user
            // ----------------------------------------------------------------
            $user = auth()->user();
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil data pembelian berdasarkan ID
            // Penjelasan: with(['mitra_bisnis']) artinya sekalian ambil
            //             data mitra_bisnis yang berhubungan dengan pembelian ini
            //             findOrFail($id) = cari berdasarkan ID, jika tidak ada error
            // ----------------------------------------------------------------
            $pembelian = pembelianModel::with(['mitra_bisnis'])->findOrFail($id);

            // Pembelian yang sudah dibayar tidak perlu diisi resinya lagi
            if ($pembelian->bukti_bayar) {
                return redirect()->route('riwayat_pembelian')
                    ->with('error', 'Pembelian ini sudah dibayar dan resi sudah dibuat.');
            }
            
            // ----------------------------------------------------------------
            // LANGKAH 3: Ambil data barang/jasa yang dibeli
            // ----------------------------------------------------------------
            $barang_jasa = barang_jasaModel::find($pembelian->barang_id);
            
            // ----------------------------------------------------------------
            // LANGKAH 4: Ambil data mitra yang menjual
            // ----------------------------------------------------------------
            $mitra_bisnis = mitra_bisnisModel::find($pembelian->mitra_id);

            // ----------------------------------------------------------------
            // LANGKAH 5: Tampilkan form isi resi dengan data yang sudah disiapkan
            // ----------------------------------------------------------------
            return view('admin.form.isi_data_resi', compact('user', 'mitra_bisnis','barang_jasa', 'pembelian'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: simpan_resi
    // TUJUAN: Menyimpan data resi yang sudah diisi oleh admin
    // PARAMETER: Request $request = data dari form yang dikirim user
    // ========================================================================
    public function simpan_resi(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Validasi data yang dikirim dari form
            // Penjelasan: Validator::make() membuat aturan validasi
            //             $request->all() = ambil semua data dari form
            // ----------------------------------------------------------------
            $validator = Validator::make($request->all(), [
                // Aturan validasi:
                'mitra_id' => 'required|exists:mitra_bisnis,mitra_id', // Harus ada & harus ada di tabel mitra_bisnis
                'pembelian_id' => 'required|exists:pembelian,pembelian_id', // Harus ada & harus ada di tabel pembelian
                'nama' => 'required', // Nama harus diisi
                'alamat' => 'required', // Alamat harus diisi
                'kontak' => 'required', // Kontak harus diisi
                'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Harus gambar dengan format tertentu
            ], [
                // Pesan error jika validasi gagal (dalam bahasa Indonesia)
                'nama.required' => 'Nama harus diisi',
                'mitra_id.exists' => 'Mitra tidak ada',
                'pembelian_id.exists' => 'Pembelian tidak ada',
                'alamat.required' => 'Alamat harus diisi',
                'kontak.required' => 'Kontak harus diisi',
                'bukti_bayar.required' => 'Bukti bayar harus diisi',
                'bukti_bayar.image' => 'Bukti bayar harus berupa gambar',
                'bukti_bayar.mimes' => 'Bukti bayar harus berformat JPEG, PNG, JPG, atau WEBP',
                'bukti_bayar.max' => 'Ukuran gambar maksimal 5 MB',
            ]);

            // ----------------------------------------------------------------
            // LANGKAH 2: Jika validasi gagal, kembalikan ke halaman sebelumnya
            // ----------------------------------------------------------------
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)    // Kirim pesan error ke view
                    ->withInput();              // Isi ulang form dengan data yang sudah diinput
            }

            // ----------------------------------------------------------------
            // LANGKAH 3: Ambil data pembelian & barang, lalu cek kelayakannya
            // Penjelasan: - Pembelian yang sudah dibayar tidak boleh dibuatkan
            //               resi lagi (mencegah stok terpotong dua kali).
            //             - Stok harus masih cukup saat pembayaran dilakukan.
            // ----------------------------------------------------------------
            $pembelian = pembelianModel::findOrFail($request->pembelian_id);

            if ($pembelian->bukti_bayar) {
                return redirect()->route('riwayat_pembelian')
                    ->with('error', 'Pembelian ini sudah dibayar dan resi sudah dibuat.');
            }

            $barang_jasa = barang_jasaModel::findOrFail($pembelian->barang_id);

            if ($barang_jasa->stok < $pembelian->jumlah_beli) {
                return redirect()->back()
                    ->with('error', 'Stok barang tidak mencukupi. Sisa stok: ' . $barang_jasa->stok)
                    ->withInput();
            }

            // ----------------------------------------------------------------
            // LANGKAH 4: Proses upload dan kompresi gambar bukti bayar
            // ----------------------------------------------------------------
            // Ambil file gambar dari form
            $file = $request->file('bukti_bayar');

            // Buat manager untuk mengolah gambar
            $manager = new ImageManager(new Driver());

            // Baca gambar dan konversi ke JPEG dengan kualitas 30% (untuk hemat storage)
            $image = $manager->read($file)->toJpeg(30);

            // Buat nama file unik menggunakan uniqid() + ekstensi .jpg
            $filename = uniqid().'.jpg';

            // Simpan gambar ke folder public/bukti_bayar
            Storage::disk('public')->put("bukti_bayar/{$filename}", $image);

            // ----------------------------------------------------------------
            // LANGKAH 5: Simpan resi, update pembelian, dan kurangi stok
            // Penjelasan: Dibungkus transaksi agar ketiganya berhasil bersama
            //             atau dibatalkan bersama.
            // ----------------------------------------------------------------
            $resi = new resiModel();
            $resi->mitra_id = $pembelian->mitra_id; // ambil dari data pembelian, bukan dari form
            $resi->pembelian_id = $pembelian->pembelian_id;
            $resi->nama = $request->nama;
            $resi->alamat = $request->alamat;
            $resi->kontak = $request->kontak;

            $statusLaporanDiubah = 0; // jumlah laporan yang statusnya ikut berubah
            DB::transaction(function () use ($resi, $pembelian, $barang_jasa, $filename, &$statusLaporanDiubah) {
                $resi->save();

                $pembelian->bukti_bayar = "bukti_bayar/{$filename}";
                $pembelian->status_bayar = 'Sudah Bayar';
                $pembelian->save();

                $barang_jasa->stok -= $pembelian->jumlah_beli; // Operator -= artinya kurangi
                if ($barang_jasa->stok <= 0) {
                    $barang_jasa->status_ketersediaan = 'Habis';
                }
                $barang_jasa->save();

                // Jika pembelian ini untuk sebuah laporan kerusakan, status laporan
                // otomatis menjadi "Diperbaiki" karena barang/jasa sudah dibayar.
                // Laporan yang sudah "Diperbaiki"/"Selesai" tidak diubah.
                if ($pembelian->laporan_id) {
                    $statusLaporanDiubah = laporan_kerusakanModel::where('laporan_id', $pembelian->laporan_id)
                        ->whereIn('status_laporan', ['Menunggu', 'Diverifikasi'])
                        ->update(['status_laporan' => 'Diperbaiki']);
                }
            });

            // ----------------------------------------------------------------
            // LANGKAH 6: Kirim email resi ke user yang melakukan pembelian
            // ----------------------------------------------------------------
            // Ambil kembali data resi lengkap dengan relasi pembelian
            $resi = resiModel::with(['pembelian'])->findOrFail($resi->resi_id);

            // Ambil email user yang login
            $email = auth()->user()->email;
            
            // Kirim email menggunakan queue (antrian) agar tidak lambat
            Mail::to($email)->queue(new ResiMail($resi));

            // ----------------------------------------------------------------
            // LANGKAH 7: Redirect ke halaman list barang dengan pesan sukses
            // ----------------------------------------------------------------
            $pesan = 'Pembelian berhasil dilakukan. Silahkan cek Email anda untuk melihat Resi.';
            if ($statusLaporanDiubah > 0) {
                $pesan .= ' Status laporan kerusakan terkait otomatis menjadi "Diperbaiki".';
            }

            return redirect()->intended(route('list_barang_jasa'))->with('success', $pesan);

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: batal_pembelian
    // TUJUAN: Membatalkan (menghapus) pembelian yang BELUM dibayar,
    //         agar tidak ada data pembelian yang menggantung.
    // PARAMETER: $id = ID pembelian yang akan dibatalkan
    // ========================================================================
    public function batal_pembelian($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil data pembelian
            // ----------------------------------------------------------------
            $pembelian = pembelianModel::findOrFail($id);

            // ----------------------------------------------------------------
            // LANGKAH 2: Pembelian yang sudah dibayar tidak boleh dibatalkan
            // ----------------------------------------------------------------
            if ($pembelian->bukti_bayar) {
                return redirect()->route('riwayat_pembelian')
                    ->with('error', 'Pembelian yang sudah dibayar tidak dapat dibatalkan.');
            }

            // ----------------------------------------------------------------
            // LANGKAH 3: Hapus pembelian
            // Penjelasan: Stok belum dikurangi (stok baru berkurang saat bayar),
            //             jadi tidak ada stok yang perlu dikembalikan.
            // ----------------------------------------------------------------
            $pembelian->delete();

            return redirect()->route('riwayat_pembelian')->with('success', 'Pembelian berhasil dibatalkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e));
        }
    }

    // ========================================================================
    // FUNCTION: beli_barangjasa
    // TUJUAN: Memproses pembelian barang/jasa oleh admin
    // PARAMETER: Request $request = data dari form pembelian
    // ========================================================================
    public function beli_barangjasa(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Validasi data yang dikirim dari form
            // Penjelasan: Validator::make() membuat aturan validasi
            //             untuk memastikan data yang dikirim lengkap dan benar
            // ----------------------------------------------------------------
            $validator = Validator::make($request->all(), [
                'barang_id' => 'required|exists:barang_jasa,barang_id', // Barang harus ada di database
                'jumlah_beli' => 'required|integer|min:1', // Jumlah beli minimal 1
                'laporan_id' => 'nullable|exists:laporan_kerusakan,laporan_id', // Laporan yang ditangani (opsional)
            ],[
                // Pesan error yang lebih spesifik jika validasi gagal
                'barang_id.required' => 'Harap pilih Barang',
                'barang_id.exists' => 'Barang tidak ditemukan',
                'jumlah_beli.required' => 'Jumlah beli harus diisi',
                'jumlah_beli.integer' => 'Jumlah beli harus berupa angka',
                'jumlah_beli.min' => 'Jumlah beli tidak boleh 0',
                'laporan_id.exists' => 'Laporan kerusakan tidak ditemukan',
            ]);
        
            // ----------------------------------------------------------------
            // LANGKAH 2: Jika validasi gagal, kembalikan ke halaman sebelumnya
            // ----------------------------------------------------------------
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)    // mengirim pesan error ke view
                    ->withInput();              // mengisi ulang data input
            }

            // ----------------------------------------------------------------
            // LANGKAH 3: Ambil data barang yang akan dibeli dari database
            // Penjelasan: findOrFail() = cari data, jika tidak ada muncul error
            // ----------------------------------------------------------------
            $barang = barang_jasaModel::findOrFail($request->barang_id);

            // ----------------------------------------------------------------
            // LANGKAH 4: Hitung total harga pembelian
            // Penjelasan: Total harga = harga per satuan × jumlah yang dibeli
            // ----------------------------------------------------------------
            if ($request->jumlah_beli > $barang->stok) {
                return redirect()->back()
                    ->with('error', 'Jumlah beli melebihi stok yang tersedia (' . $barang->stok . ').');
            }

            $total_harga = $barang->harga_satuan * $request->jumlah_beli;

            // ----------------------------------------------------------------
            // LANGKAH 5: Buat data pembelian baru di database
            // Penjelasan: Simpan semua informasi pembelian ke tabel pembelian
            // ----------------------------------------------------------------
            $pembelian = new pembelianModel();
            // mitra, nama barang, dan jenis diambil dari database agar tidak bisa dimanipulasi
            $pembelian->mitra_id = $barang->mitra_id;
            $pembelian->barang_id = $barang->barang_id;
            $pembelian->nama_barang = $barang->nama_barang;
            $pembelian->jenis = $barang->jenis;
            $pembelian->jumlah_beli = $request->jumlah_beli;
            $pembelian->laporan_id = $request->laporan_id ?: null; // kaitkan dengan laporan kerusakan
            $pembelian->total_harga = $total_harga; 
            $pembelian->save();
            

            // ----------------------------------------------------------------
            // LANGKAH 6: Redirect ke halaman isi data resi dengan pesan sukses
            // Penjelasan: Setelah beli, langsung ke form isi resi pengiriman
            //             $pembelian->pembelian_id = ID pembelian yang baru dibuat
            // ----------------------------------------------------------------
            return redirect()->intended(route('isi_data_resi', $pembelian->pembelian_id));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR: Jika terjadi kesalahan di proses manapun
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: ubah_status_laporan
    // TUJUAN: Mengubah status laporan kerusakan (misal: Pending → Selesai)
    // ========================================================================
    public function ubah_status_laporan(Request $request, $id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Cari laporan berdasarkan ID
            // ----------------------------------------------------------------
            $laporan = laporan_kerusakanModel::findOrFail($id);
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Update status laporan dengan data dari form
            // ----------------------------------------------------------------
            if (!in_array($request->status_laporan, ['Menunggu', 'Diverifikasi', 'Diperbaiki', 'Selesai'])) {
                return redirect()->back()->with('error', 'Status laporan tidak valid.');
            }

            $laporan->status_laporan = $request->status_laporan;
            $laporan->save();

            // ----------------------------------------------------------------
            // LANGKAH 3: Kembalikan ke halaman sebelumnya dengan pesan sukses
            // ----------------------------------------------------------------
            return redirect()->back()->with('success', 'Status laporan berhasil diubah.');

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: hapus_barangjasa_admin
    // TUJUAN: Menghapus data barang/jasa dari database
    // ========================================================================
    public function hapus_barangjasa_admin($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Cari barang berdasarkan ID
            // ----------------------------------------------------------------
            $barang = barang_jasaModel::findOrFail($id);
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Hapus barang dari database
            // Penjelasan: delete() akan menghapus data permanen
            // ----------------------------------------------------------------
            $barang->delete();

            // Hapus juga file foto produk dari storage
            if ($barang->foto_produk) {
                Storage::disk('public')->delete($barang->foto_produk);
            }

            // ----------------------------------------------------------------
            // LANGKAH 3: Kembalikan dengan pesan sukses
            // ----------------------------------------------------------------
            return redirect()->back()->with('success', 'Barang/jasa berhasil dihapus.');

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: verifikasi_akun
    // TUJUAN: Memverifikasi akun mitra yang mendaftar (approve pendaftaran)
    // ========================================================================
    public function verifikasi_akun(Request $request, $id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil data mitra yang belum terverifikasi
            // Penjelasan: Data diambil dari DATABASE, bukan dari hidden input
            //             form, agar tidak bisa dimanipulasi lewat request.
            // ----------------------------------------------------------------
            $mitra = unverified_akun_mitraModel::findOrFail($id);

            // ----------------------------------------------------------------
            // LANGKAH 2: Pastikan username & email belum dipakai akun lain
            // ----------------------------------------------------------------
            $validator = Validator::make($mitra->toArray(), [
                'username' => 'required|unique:user,username',
                'email' => 'required|email|unique:mitra_bisnis,email',
                'kategori' => 'required|in:Vendor Barang,Vendor Jasa',
            ],[
                'username.unique' => 'Username sudah terpakai',
                'email.unique' => 'Email sudah terdaftar',
                'kategori.required' => 'Kategori mitra belum diisi',
                'kategori.in' => 'Kategori mitra tidak valid',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ----------------------------------------------------------------
            // LANGKAH 3: Password sudah di-hash saat registrasi.
            // Data lama yang masih plaintext di-hash di sini.
            // ----------------------------------------------------------------
            $password = $mitra->password;
            if (password_get_info($password)['algo'] === null) {
                $password = Hash::make($password);
            }

            // ----------------------------------------------------------------
            // LANGKAH 4: Buat akun user + data mitra bisnis dalam satu transaksi
            // Penjelasan: Jika salah satu gagal, semuanya dibatalkan sehingga
            //             tidak ada akun user "yatim" tanpa data mitra.
            // ----------------------------------------------------------------
            DB::transaction(function () use ($mitra, $password) {
                $user = new userModel();
                $user->nama = $mitra->nama;
                $user->username = $mitra->username;
                $user->kontak = $mitra->kontak;
                $user->email = $mitra->email;
                $user->password = $password;
                $user->role = 'Mitra';
                $user->save();

                $mitra_bisnis = new mitra_bisnisModel();
                $mitra_bisnis->user_id = $user->user_id;
                $mitra_bisnis->nama_mitra = $mitra->nama;
                $mitra_bisnis->alamat = $mitra->alamat;
                $mitra_bisnis->kontak = $mitra->kontak;
                $mitra_bisnis->email = $mitra->email;
                $mitra_bisnis->no_rekening = $mitra->no_rekening;
                $mitra_bisnis->nama_bank = $mitra->nama_bank;
                $mitra_bisnis->kategori = $mitra->kategori;
                $mitra_bisnis->save();

                $mitra->delete();
            });

            // ----------------------------------------------------------------
            // LANGKAH 5: Kirim email notifikasi verifikasi ke mitra
            // ----------------------------------------------------------------
            // Pakai send() (bukan queue) karena data $mitra sudah dihapus dari DB,
            // sehingga tidak bisa di-serialize ulang oleh antrean.
            try {
                Mail::to($mitra->email)->send(new VerifikasiAkunMitraMail($mitra));
            } catch (\Exception $e) {
                return redirect()->back()->with('success', 'Akun mitra berhasil diverifikasi, tetapi email notifikasi gagal dikirim.');
            }

            // ----------------------------------------------------------------
            // LANGKAH 6: Kembalikan dengan pesan sukses
            // ----------------------------------------------------------------
            return redirect()->back()->with('success', 'Akun mitra berhasil diverifikasi.');

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: hapus_unverified_mitra
    // TUJUAN: Menolak/menghapus pendaftaran mitra yang belum terverifikasi
    // ========================================================================
    public function hapus_unverified_mitra ($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Cari dan hapus data mitra yang belum terverifikasi
            // ----------------------------------------------------------------
            $unverified_mitra = unverified_akun_mitraModel::findOrFail($id);
            $unverified_mitra->delete();
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Kembalikan dengan pesan sukses
            // ----------------------------------------------------------------
            return redirect()->back()->with('success', 'Akun mitra berhasil ditolak.');

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: pencarian_laporan
    // TUJUAN: Mencari laporan berdasarkan kata kunci yang diinput user
    // ========================================================================
    public function pencarian_laporan(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil kata kunci pencarian dari form
            // ----------------------------------------------------------------
            $search = $request->search;
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Cari di database berdasarkan beberapa kolom
            // Penjelasan: where() dan orWhere() untuk mencari di berbagai kolom
            //             'like' artinya cocok sebagian (tidak harus sama persis)
            //             "%$search%" artinya kata kunci bisa di awal, tengah, atau akhir
            // ----------------------------------------------------------------
            $laporan_all = laporan_kerusakanModel::with('pelapor')->withCount('pembelian')
                                            ->where(DB::raw('DATE_FORMAT(tanggal_lapor, "%d-%b-%Y")'), 'like', "%$search%")
                                            ->orWhere('lokasi_fasilitas', 'like', "%$search%")
                                            ->orWhere('deskripsi_kerusakan', 'like', "%$search%")
                                            ->orWhere('status_laporan', 'like', "%$search%")
                                            // cari juga berdasarkan nama pelapor
                                            ->orWhereHas('pelapor', function ($query) use ($search) {
                                                $query->where('nama', 'like', "%$search%");
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(6)->withQueryString();

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan hasil pencarian
            // ----------------------------------------------------------------
            return view('admin.page.laporan_kerusakan', compact('laporan_all'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: pencarian_barangjasa
    // TUJUAN: Mencari barang/jasa berdasarkan kata kunci
    // ========================================================================
    public function pencarian_barangjasa(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil kata kunci pencarian
            // ----------------------------------------------------------------
            $search = $request->search;
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Cari di berbagai kolom tabel barang_jasa
            // ----------------------------------------------------------------
            $laporan_aktif = $this->laporanUntukPembelian();
            $barang_jasa = barang_jasaModel::with('mitra')->where('nama_barang', 'like', "%$search%")
                                            ->orWhere('deskripsi', 'like', "%$search%")
                                            ->orWhere('jenis', 'like', "%$search%")
                                            ->orWhere('stok', 'like', "%$search%")
                                            ->orWhere('harga_satuan', 'like', "%$search%")
                                            ->orderBy('created_at', 'desc')->paginate(6)->withQueryString();

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan hasil
            // ----------------------------------------------------------------
            return view('admin.page.list_barang_jasa', compact('barang_jasa', 'laporan_aktif'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: pencarian_mitra_belum_verifikasi
    // TUJUAN: Mencari mitra yang belum terverifikasi
    // ========================================================================
    public function pencarian_mitra_belum_verifikasi(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil kata kunci
            // ----------------------------------------------------------------
            $search = $request->search;
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Cari berdasarkan nama atau email
            // ----------------------------------------------------------------
            $mitra_belum_verifikasi_all = unverified_akun_mitraModel::where('nama', 'like', "%$search%")
                                                                    ->orWhere('email', 'like', "%$search%")
                                                                    ->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan hasil
            // ----------------------------------------------------------------
            return view('admin.page.mitra_belum_terverifikasi', compact('mitra_belum_verifikasi_all'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: pencarian_riwayat_pembelian
    // TUJUAN: Mencari riwayat pembelian berdasarkan kata kunci
    // ========================================================================
    public function pencarian_riwayat_pembelian(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil kata kunci
            // ----------------------------------------------------------------
            $search = $request->search;
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Cari di berbagai kolom termasuk tanggal
            // Penjelasan: DB::raw('DATE_FORMAT(...)') untuk format tanggal
            //             supaya bisa dicari dengan format yang mudah dibaca
            // ----------------------------------------------------------------
            $pembelian_all = pembelianModel::with('laporan')->where('nama_barang', 'like', "%$search%")
                                            ->orWhere('total_harga', 'like', "%$search%")
                                            ->orWhere('jenis', 'like', "%$search%")
                                            ->orWhere(DB::raw('DATE_FORMAT(created_at, "%d %b %Y")'), 'like', "%$search%")
                                            ->orderBy('created_at', 'desc')->paginate(6)->withQueryString();

            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan hasil
            // ----------------------------------------------------------------
            return view('admin.page.riwayat_pembelian', compact('pembelian_all'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }
}