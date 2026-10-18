<?php

namespace Database\Seeders;

use App\Models\barang_jasaModel;
use App\Models\laporan_kerusakanModel;
use App\Models\mitra_bisnisModel;
use App\Models\pembelianModel;
use App\Models\resiModel;
use App\Models\unverified_akun_mitraModel;
use App\Models\userModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class DatabaseSeeder extends Seeder
{
    // ========================================================================
    // Data awal (demo) untuk instalasi baru:
    //   php artisan migrate --seed
    //
    // Akun yang dibuat (password = username + "123"):
    //   admin / admin123        → Admin
    //   pegawai / pegawai123    → Pegawai
    //   tamu / tamu123          → Tamu
    //   mitra / mitra123        → Mitra (sudah terverifikasi)
    //
    // Memakai updateOrCreate sehingga aman dijalankan lebih dari sekali.
    // ========================================================================
    public function run(): void
    {
        $this->akun('admin', 'Administrator', 'Admin', 'admin@hotel.test');
        $pegawai = $this->akun('pegawai', 'Budi Santoso', 'Pegawai', 'pegawai@hotel.test');
        $tamu = $this->akun('tamu', 'Siti Rahma', 'Tamu', 'tamu@hotel.test');
        $userMitra = $this->akun('mitra', 'CV Teknik Jaya', 'Mitra', 'mitra@hotel.test');

        $mitra = mitra_bisnisModel::updateOrCreate(
            ['user_id' => $userMitra->user_id],
            [
                'nama_mitra' => 'CV Teknik Jaya',
                'alamat' => 'Jl. Merdeka No. 10',
                'kontak' => '081234567890',
                'email' => 'mitra@hotel.test',
                'nama_bank' => 'BCA',
                'no_rekening' => '1234567890',
                'kategori' => 'Vendor Jasa',
            ]
        );

        // ---------------------------------------------------------------- Barang & jasa milik mitra
        $barang = [];
        foreach ([
            ['Lampu LED 12 Watt', 'Lampu LED hemat energi untuk kamar dan koridor', 'Barang', 50, 35000, null],
            ['Kran Wastafel Stainless', 'Kran wastafel bahan stainless anti karat', 'Barang', 20, 150000, null],
            ['Jasa Service AC', 'Cuci, cek tekanan, dan isi freon AC split per unit', 'Jasa', 100, 125000, 'area-ac.jpg'],
            ['Jasa Pemeriksaan Lift', 'Pemeriksaan rutin & pelumasan sistem lift', 'Jasa', 10, 750000, 'area-lift.jpg'],
        ] as [$nama, $deskripsi, $jenis, $stok, $harga, $foto]) {
            $barang[$nama] = barang_jasaModel::updateOrCreate(
                ['mitra_id' => $mitra->mitra_id, 'nama_barang' => $nama],
                [
                    'deskripsi' => $deskripsi,
                    'jenis' => $jenis,
                    'stok' => $stok,
                    'harga_satuan' => $harga,
                    'status_ketersediaan' => 'Tersedia',
                    'foto_produk' => $this->gambar('foto_produk', $nama, $foto),
                ]
            );
        }

        // ---------------------------------------------------------------- Laporan kerusakan (berbagai status)
        $laporan = [];
        foreach ([
            [$pegawai, 'Kamar 204', 'AC tidak dingin dan mengeluarkan suara berisik sejak semalam.', 'Diperbaiki', 6, 'area-ac.jpg'],
            [$tamu, 'Lift Tamu Lobby', 'Pintu lift lambat menutup dan sesekali berhenti di antara lantai.', 'Diverifikasi', 3, 'area-lift.jpg'],
            [$pegawai, 'Ruang Genset', 'Genset cadangan gagal menyala saat uji rutin mingguan.', 'Menunggu', 1, 'area-genset.jpg'],
            [$tamu, 'Area Kolam Renang', 'Beberapa lampu bawah air di kolam renang mati.', 'Selesai', 12, 'hero-hotel.jpg'],
        ] as [$pelapor, $lokasi, $deskripsi, $status, $hariLalu, $foto]) {
            $laporan[$lokasi] = laporan_kerusakanModel::updateOrCreate(
                ['user_id' => $pelapor->user_id, 'lokasi_fasilitas' => $lokasi],
                [
                    'tanggal_lapor' => now()->subDays($hariLalu)->toDateString(),
                    'deskripsi_kerusakan' => $deskripsi,
                    'status_laporan' => $status,
                    'foto_kerusakan' => $this->gambar('foto_kerusakan', $lokasi, $foto),
                ]
            );
        }

        // ---------------------------------------------------------------- Pembelian untuk laporan
        // Sudah dibayar → laporan "Kamar 204" berstatus Diperbaiki
        $lunas = pembelianModel::updateOrCreate(
            ['barang_id' => $barang['Jasa Service AC']->barang_id, 'laporan_id' => $laporan['Kamar 204']->laporan_id],
            [
                'mitra_id' => $mitra->mitra_id,
                'nama_barang' => 'Jasa Service AC',
                'jenis' => 'Jasa',
                'jumlah_beli' => 1,
                'total_harga' => 125000,
                'status_pembelian' => 'Diproses',
                'status_bayar' => 'Sudah Bayar',
                'bukti_bayar' => $this->gambar('bukti_bayar', 'bukti-service-ac', null),
            ]
        );
        resiModel::updateOrCreate(
            ['pembelian_id' => $lunas->pembelian_id],
            ['mitra_id' => $mitra->mitra_id, 'nama' => 'Administrator', 'alamat' => 'Departemen Maintenance', 'kontak' => '081234567890']
        );

        // Belum dibayar → bisa dilanjutkan / dibatalkan dari Riwayat Pembelian
        pembelianModel::updateOrCreate(
            ['barang_id' => $barang['Jasa Pemeriksaan Lift']->barang_id, 'laporan_id' => $laporan['Lift Tamu Lobby']->laporan_id],
            [
                'mitra_id' => $mitra->mitra_id,
                'nama_barang' => 'Jasa Pemeriksaan Lift',
                'jenis' => 'Jasa',
                'jumlah_beli' => 1,
                'total_harga' => 750000,
            ]
        );

        // ---------------------------------------------------------------- Calon mitra menunggu verifikasi
        unverified_akun_mitraModel::updateOrCreate(
            ['username' => 'sumberlistrik'],
            [
                'nama' => 'UD Sumber Listrik',
                'password' => Hash::make('sumberlistrik123'),
                'alamat' => 'Jl. Diponegoro No. 25',
                'kontak' => '082233445566',
                'email' => 'sumberlistrik@vendor.test',
                'nama_bank' => 'BRI',
                'no_rekening' => '9876543210',
                'kategori' => 'Vendor Barang',
            ]
        );
    }

    // Membuat / memperbarui satu akun login
    private function akun(string $username, string $nama, string $role, string $email): userModel
    {
        $user = userModel::firstOrNew(['username' => $username]);
        $user->nama = $nama;
        $user->password = Hash::make($username . '123');
        $user->role = $role;
        $user->kontak = '081234567890';
        $user->email = $email;
        $user->save();

        return $user;
    }

    // Menyalin foto contoh dari public/img/landing ke storage/app/public/{folder}.
    // Jika $foto kosong, dibuat gambar polos sebagai placeholder.
    private function gambar(string $folder, string $nama, ?string $foto): string
    {
        $path = $folder . '/contoh-' . md5($nama) . '.jpg';

        if (!Storage::disk('public')->exists($path)) {
            $sumber = $foto ? public_path('img/landing/' . $foto) : null;

            $isi = ($sumber && is_file($sumber))
                ? file_get_contents($sumber)
                : (new ImageManager(new Driver()))->create(640, 480)->fill('#DBE3EE')->toJpeg(70);

            Storage::disk('public')->put($path, $isi);
        }

        return $path;
    }
}
