<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang_jasaModel;
use App\Models\mitra_bisnisModel;
use App\Models\pembelianModel;
use App\Models\resiModel;
use App\Models\laporan_kerusakanModel;
use App\Mail\StatusDiprosesMail;
use App\Mail\StatusSelesaiMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 

class mitraController extends Controller
{
    // ========================================================================
    // FUNCTION: mitraLogin
    // TUJUAN: Mengambil data mitra_bisnis milik user yang sedang login
    // ========================================================================
    private function mitraLogin()
    {
        return mitra_bisnisModel::where('user_id', auth()->id())->first();
    }

    // ========================================================================
    // FUNCTION: mitraTidakDitemukan
    // TUJUAN: Respon jika akun mitra belum punya data mitra_bisnis
    // ========================================================================
    private function mitraTidakDitemukan()
    {
        auth()->logout();
        return redirect()->route('login')
            ->with('error', 'Data mitra untuk akun ini tidak ditemukan. Silahkan hubungi admin.');
    }


    // ========================================================================
    // FUNCTION: barang_jasa
    // TUJUAN: Menampilkan daftar barang/jasa milik mitra yang sedang login
    // ========================================================================
    public function barang_jasa()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil Data Identitas Mitra
            // Penjelasan: Kita perlu tahu 'user' yang login ini terhubung ke data 'mitra' yang mana.
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil Barang/Jasa Milik Mitra Tersebut
            // Penjelasan: 
            // - where('mitra_id', ...) = Hanya ambil barang punya mitra ini.
            // - orderBy(...) = Urutkan dari yang paling baru diinput.
            // ----------------------------------------------------------------
            $barang_jasa = barang_jasaModel::where('mitra_id', $mitra_bisnis->mitra_id)->orderBy('created_at', 'desc')->paginate(10);
            
            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan View
            // ----------------------------------------------------------------
            return view('mitra.page.barang_jasa', compact('barang_jasa'));

        } catch (\Exception $e) {
            // ----------------------------------------------------------------
            // PENANGANAN ERROR
            // ----------------------------------------------------------------
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: laporan_kerusakan
    // TUJUAN: Mitra melihat daftar laporan kerusakan (Mungkin untuk menawarkan jasa perbaikan)
    // ========================================================================
    public function laporan_kerusakan()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil Semua Laporan Kerusakan
            // ----------------------------------------------------------------
            $laporan_all = laporan_kerusakanModel::orderBy('tanggal_lapor', 'desc')->paginate(6);
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Tampilkan View
            // ----------------------------------------------------------------
            return view('mitra.page.laporan_kerusakan', compact('laporan_all'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: orderan
    // TUJUAN: Menampilkan pesanan masuk yang SUDAH DIBAYAR tapi belum selesai
    // ========================================================================
    public function orderan()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Identifikasi Mitra
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil Data Pembelian Masuk
            // Penjelasan:
            // - where('mitra_id', ...) = Hanya pesanan untuk mitra ini.
            // - where('status_bayar', 'Sudah Bayar') = Hanya yang sudah lunas.
            // - where('status_pembelian', '!=', 'Selesai') = Hanya menampilkan data yang status pembeliannya selain 'Selesai'.
            // ----------------------------------------------------------------
            $pembelian_all = pembelianModel::where('mitra_id', $mitra_bisnis->mitra_id)
                                           ->where('status_bayar', 'Sudah Bayar')
                                           ->where('status_pembelian', '!=', 'Selesai')
                                           ->orderBy('created_at', 'desc')
                                           ->paginate(10);
            
            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan View
            // ----------------------------------------------------------------
            return view('mitra.page.orderan', compact('pembelian_all'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: histori_orderan
    // TUJUAN: Menampilkan pesanan yang sudah berstatus 'Selesai'
    // ========================================================================
    public function histori_orderan()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Identifikasi Mitra
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil Data Pembelian Selesai
            // ----------------------------------------------------------------
            $pembelian_selesai = pembelianModel::where('mitra_id', $mitra_bisnis->mitra_id)
                                               ->where('status_pembelian', 'Selesai')
                                               ->orderBy('created_at', 'desc')
                                               ->paginate(10);
            
            // ----------------------------------------------------------------
            // LANGKAH 3: Tampilkan View
            // ----------------------------------------------------------------
            return view('mitra.page.histori_orderan', compact('pembelian_selesai'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: tambah
    // TUJUAN: Menampilkan form tambah barang/jasa baru
    // ========================================================================
    public function tambah()
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            return view('mitra.form.tambah');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: edit
    // TUJUAN: Menampilkan form edit untuk barang/jasa tertentu
    // ========================================================================
    public function edit($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // Cari barang berdasarkan ID
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // Hanya barang milik mitra yang login yang boleh diakses
            $barang_jasa = barang_jasaModel::where('mitra_id', $mitra_bisnis->mitra_id)->find($id);
            if (!$barang_jasa) {
                return redirect()->route('barang_jasa')
                    ->with('error', 'Barang/Jasa tidak ditemukan atau bukan milik Anda.');
            }
            
            return view('mitra.form.edit', compact('barang_jasa'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: update
    // TUJUAN: Memproses perubahan data barang/jasa (termasuk ganti gambar)
    // ========================================================================
    public function update(Request $request, $id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Validasi Input (Gaya Admin Controller)
            // ----------------------------------------------------------------
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'required|string|max:150',
                'deskripsi' => 'required',
                'jenis' => 'required|string|max:150',
                'stok' => 'required|integer|min:0',
                'harga_satuan' => 'required|numeric|min:0',
                'status_ketersediaan' => 'required|in:Tersedia,Habis',
                'foto_produk' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // Tidak required, karena mungkin tidak ganti foto
            ], [
                'nama_barang.required' => 'Nama barang harus diisi',
                'deskripsi.required' => 'Deskripsi harus diisi',
                'jenis.required' => 'Jenis barang harus dipilih',
                'stok.required' => 'Stok harus diisi',
                'stok.integer' => 'Stok harus berupa angka bulat',
                'harga_satuan.required' => 'Harga harus diisi',
                'status_ketersediaan.required' => 'Status ketersediaan harus dipilih',
                'foto_produk.image' => 'File harus berupa gambar',
                'foto_produk.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
                'foto_produk.max' => 'Ukuran gambar maksimal 5 MB',
            ]);

            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Cari Barang Lama
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // Hanya barang milik mitra yang login yang boleh diakses
            $barang_jasa = barang_jasaModel::where('mitra_id', $mitra_bisnis->mitra_id)->find($id);
            if (!$barang_jasa) {
                return redirect()->route('barang_jasa')
                    ->with('error', 'Barang/Jasa tidak ditemukan atau bukan milik Anda.');
            }
            
            // ----------------------------------------------------------------
            // LANGKAH 3: Update Informasi Teks
            // ----------------------------------------------------------------
            $barang_jasa->nama_barang = $request->nama_barang;
            $barang_jasa->deskripsi = $request->deskripsi;
            $barang_jasa->jenis = $request->jenis;
            $barang_jasa->stok = $request->stok;
            $barang_jasa->harga_satuan = $request->harga_satuan;
            $barang_jasa->status_ketersediaan = $request->status_ketersediaan;

            // ----------------------------------------------------------------
            // LANGKAH 4: Cek & Proses Ganti Foto
            // ----------------------------------------------------------------
            if ($request->hasFile('foto_produk')) {
                // Hapus foto produk lama dari penyimpanan jika ada
                if ($barang_jasa->foto_produk) {
                    Storage::disk('public')->delete($barang_jasa->foto_produk);
                }
                
                // Upload foto baru
                $file = $request->file('foto_produk');
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file)->toJpeg(30); // compress ke JPG kualitas 30%
                $filename = uniqid().'.jpg';
                
                // Simpan ke storage
                Storage::disk('public')->put("foto_produk/{$filename}", $image);
                
                // Update path di database
                $barang_jasa->foto_produk = "foto_produk/{$filename}";
            }

            // ----------------------------------------------------------------
            // LANGKAH 5: Simpan Perubahan
            // ----------------------------------------------------------------
            $barang_jasa->save();

            return redirect()->intended('/barang_jasa')->with('success', 'Barang/Jasa berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: simpan
    // TUJUAN: Menyimpan barang/jasa baru yang diinput mitra
    // ========================================================================
    public function simpan(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Validasi Input (Gaya Admin Controller)
            // ----------------------------------------------------------------
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'required|string|max:150',
                'deskripsi' => 'required',
                'jenis' => 'required|string|max:150',
                'stok' => 'required|integer|min:0',
                'harga_satuan' => 'required|numeric|min:0',
                'foto_produk' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Wajib ada untuk barang baru
            ], [
                'nama_barang.required' => 'Nama barang harus diisi',
                'deskripsi.required' => 'Deskripsi harus diisi',
                'jenis.required' => 'Jenis barang harus dipilih',
                'stok.required' => 'Stok harus diisi',
                'harga_satuan.required' => 'Harga harus diisi',
                'foto_produk.required' => 'Foto produk wajib diupload',
                'foto_produk.image' => 'File harus berupa gambar',
                'foto_produk.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
                'foto_produk.max' => 'Ukuran gambar maksimal 5 MB',
            ]);

            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ----------------------------------------------------------------
            // LANGKAH 2: Ambil Data Mitra
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // ----------------------------------------------------------------
            // LANGKAH 3: Proses Upload Foto
            // ----------------------------------------------------------------
            $file = $request->file('foto_produk');

            $manager = new ImageManager(new Driver());

            $image = $manager->read($file)->toJpeg(30); // compress ke JPG kualitas 30%

            $filename = uniqid().'.jpg';

            // simpan ke storage public
            Storage::disk('public')->put("foto_produk/{$filename}", $image);

            // ----------------------------------------------------------------
            // LANGKAH 4: Simpan ke Database
            // ----------------------------------------------------------------
            $barang_jasa = new barang_jasaModel();
            $barang_jasa->mitra_id = $mitra_bisnis->mitra_id; // Link ke mitra yg login
            $barang_jasa->nama_barang = $request->nama_barang;
            $barang_jasa->deskripsi = $request->deskripsi;
            $barang_jasa->jenis = $request->jenis;
            $barang_jasa->stok = $request->stok;
            $barang_jasa->harga_satuan = $request->harga_satuan;
            $barang_jasa->status_ketersediaan = $request->stok > 0 ? 'Tersedia' : 'Habis';
            $barang_jasa->foto_produk = "foto_produk/{$filename}";
            $barang_jasa->save();

            // ----------------------------------------------------------------
            // LANGKAH 5: Redirect Sukses
            // ----------------------------------------------------------------
            return redirect()->intended('/barang_jasa')->with('success', 'Barang/Jasa berhasil disimpan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: hapus
    // TUJUAN: Menghapus data barang/jasa
    // ========================================================================
    public function hapus($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Cari & Hapus
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // Hanya barang milik mitra yang login yang boleh diakses
            $barang_jasa = barang_jasaModel::where('mitra_id', $mitra_bisnis->mitra_id)->find($id);
            if (!$barang_jasa) {
                return redirect()->route('barang_jasa')
                    ->with('error', 'Barang/Jasa tidak ditemukan atau bukan milik Anda.');
            }
            // Opsional: Sebaiknya hapus juga file fotonya dari storage jika diperlukan
            // if ($barang_jasa->foto_produk) { Storage::disk('public')->delete($barang_jasa->foto_produk); }
            
            $barang_jasa->delete();

            // Hapus juga file foto produk dari storage
            if ($barang_jasa->foto_produk) {
                Storage::disk('public')->delete($barang_jasa->foto_produk);
            }

            return redirect()->back()->with('success', 'Barang/Jasa berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: ubah_status_pembelian
    // TUJUAN: Mitra mengubah status pesanan (misal: dari Dibayar -> Diproses)
    // ========================================================================
    public function ubah_status_pembelian(Request $request, $id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Update Status
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // Hanya pesanan milik mitra yang login yang boleh diubah
            $pembelian = pembelianModel::where('mitra_id', $mitra_bisnis->mitra_id)->find($id);
            if (!$pembelian) {
                return redirect()->route('orderan')
                    ->with('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');
            }

            if (!in_array($request->status_pembelian, ['Menunggu', 'Diproses', 'Selesai'])) {
                return redirect()->back()->with('error', 'Status pembelian tidak valid.');
            }

            $pembelian->status_pembelian = $request->status_pembelian;
            $pembelian->save();

            // ----------------------------------------------------------------
            // LANGKAH 2: Kirim Email Notifikasi
            // Penjelasan: Mengirim email pemberitahuan update status menggunakan Queue (antrian)
            // ----------------------------------------------------------------
            if ($request->status_pembelian == 'Diproses') {
                $email = auth()->user()->email; // Catatan: Ini mengirim email ke Mitra sendiri (sesuai kode asli)
                Mail::to($email)->queue(new StatusDiprosesMail($pembelian));
            } elseif ($request->status_pembelian == 'Selesai') {
                $email = auth()->user()->email;
                Mail::to($email)->queue(new StatusSelesaiMail($pembelian));
            }

            return redirect()->back()->with('success', 'Status pembelian berhasil diubah.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: resiPdf
    // TUJUAN: Mencetak Resi dalam bentuk PDF
    // ========================================================================
    public function resiPdf($id)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Ambil Data Resi & Pembelian
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }

            // Hanya resi milik mitra yang login yang boleh dilihat
            $resi = resiModel::with('pembelian')
                ->where('pembelian_id', $id)
                ->where('mitra_id', $mitra_bisnis->mitra_id)
                ->first();

            if (!$resi) {
                return redirect()->back()->with('error', 'Resi tidak ditemukan.');
            }
            
            // ----------------------------------------------------------------
            // LANGKAH 2: Generate PDF
            // Penjelasan: Menggunakan library DomPDF untuk mengubah view HTML jadi PDF
            // ----------------------------------------------------------------
            $pdf = Pdf::loadView('mitra.print.resi', compact('resi'));
            
            // Stream artinya PDF langsung terbuka di browser (tidak langsung download)
            return $pdf->stream($resi->resi_id . '.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }

    }

    // ========================================================================
    // FUNCTION: pencarian_barangjasa_mitra
    // TUJUAN: Mencari barang/jasa milik mitra tertentu
    // ========================================================================
    public function pencarian_barangjasa_mitra(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            // ----------------------------------------------------------------
            // LANGKAH 1: Cari Barang
            // Penjelasan: 
            // - Kita pakai grouping (function($query)) agar logika OR (...) tidak merusak
            //   filter where('mitra_id'). Jadi: Mitra INI DAN (Nama cocok ATAU Harga cocok ...)
            // ----------------------------------------------------------------
            $mitra_bisnis = $this->mitraLogin();
            if (!$mitra_bisnis) {
                return $this->mitraTidakDitemukan();
            }
            
            $barang_jasa = barang_jasaModel::where('mitra_id', $mitra_bisnis->mitra_id)
            ->where(function ($query) use ($request) {
                $query->where('nama_barang', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                ->orWhere('jenis', 'like', '%' . $request->search . '%')
                ->orWhere('stok', 'like', '%' . $request->search . '%')
                ->orWhere('harga_satuan', 'like', '%' . $request->search . '%')
                ->orWhere('status_ketersediaan', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            
            return view('mitra.page.barang_jasa', compact('barang_jasa'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: pencarian_laporan_kerusakan_mitra
    // TUJUAN: Mencari laporan kerusakan
    // ========================================================================
    public function pencarian_laporan_kerusakan_mitra(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            $mitra_bisnis = $this->mitraLogin();

            if (!$mitra_bisnis) {

                return $this->mitraTidakDitemukan();

            }
            
            $laporan_all = laporan_kerusakanModel::where(function ($query) use ($request) {
                $query->where('lokasi_fasilitas', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi_kerusakan', 'like', '%' . $request->search . '%')
                ->orWhere('status_laporan', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')->paginate(6)->withQueryString();
            
            return view('mitra.page.laporan_kerusakan', compact('laporan_all'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: pencarian_orderan
    // TUJUAN: Mencari orderan aktif
    // ========================================================================
    public function pencarian_orderan(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php

            $mitra_bisnis = $this->mitraLogin();

            if (!$mitra_bisnis) {

                return $this->mitraTidakDitemukan();

            }
            
            $pembelian_all = pembelianModel::where('mitra_id', $mitra_bisnis->mitra_id)->where('status_bayar', 'Sudah Bayar')
            ->where(function ($query) use ($request) {
                $query->where('nama_barang', 'like', '%' . $request->search . '%')
                ->orWhere(DB::raw('DATE_FORMAT(created_at, "%d %b %Y")'), 'like', "%$request->search%")
                ->orWhere('jenis', 'like', '%' . $request->search . '%')
                ->orWhere('total_harga', 'like', '%' . $request->search . '%')
                ->orWhere('status_pembelian', 'like', '%' . $request->search . '%')
                ->orWhere('status_bayar', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            
            return view('mitra.page.orderan', compact('pembelian_all'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }

    // ========================================================================
    // FUNCTION: pencarian_histori_orderan
    // TUJUAN: Mencari histori orderan selesai
    // ========================================================================
    public function pencarian_histori_orderan(Request $request)
    {
        try {
            // Hak akses role sudah dicek oleh middleware 'role' di routes/web.php
            
            $mitra_bisnis = $this->mitraLogin();
            
            if (!$mitra_bisnis) {
            
                return $this->mitraTidakDitemukan();
            
            }
            
            $pembelian_selesai = pembelianModel::where('mitra_id', $mitra_bisnis->mitra_id)->where('status_pembelian', 'Selesai')
            ->where(function ($query) use ($request) {
                $query->where('nama_barang', 'like', '%' . $request->search . '%')
                ->orWhere(DB::raw('DATE_FORMAT(created_at, "%d %b %Y")'), 'like', "%$request->search%")
                ->orWhere('jenis', 'like', '%' . $request->search . '%')
                ->orWhere('total_harga', 'like', '%' . $request->search . '%')
                ->orWhere('status_pembelian', 'like', '%' . $request->search . '%')
                ->orWhere('status_bayar', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            
            return view('mitra.page.histori_orderan', compact('pembelian_selesai'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $this->pesanError($e, 'Terjadi kesalahan'));
        }
    }
}