<?php

namespace Tests;

use App\Models\barang_jasaModel;
use App\Models\laporan_kerusakanModel;
use App\Models\mitra_bisnisModel;
use App\Models\pembelianModel;
use App\Models\userModel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // Pengaman: RefreshDatabase mengosongkan database, jadi pastikan test
    // TIDAK PERNAH berjalan di database asli (misal saat config ter-cache).
    protected function setUpTraits()
    {
        $database = config('database.connections.' . config('database.default') . '.database');

        if (!str_ends_with((string) $database, '_test')) {
            throw new \RuntimeException("Test dihentikan: database '{$database}' bukan database test. Jalankan 'php artisan config:clear' lalu ulangi.");
        }

        return parent::setUpTraits();
    }

    // ========================================================================
    // Helper pembuat data untuk test. Password semua akun test = "rahasia123"
    // ========================================================================

    protected function buatUser(string $role, array $data = []): userModel
    {
        static $urut = 0;
        $urut++;

        $user = new userModel();
        $user->nama = $data['nama'] ?? "User {$role} {$urut}";
        $user->username = $data['username'] ?? strtolower($role) . "_test_{$urut}";
        $user->password = Hash::make($data['password'] ?? 'rahasia123');
        $user->role = $role;
        $user->kontak = $data['kontak'] ?? '081234567890';
        $user->email = $data['email'] ?? strtolower($role) . "{$urut}@test.local";
        $user->aktif = $data['aktif'] ?? true;
        $user->save();

        return $user;
    }

    // Membuat akun Mitra lengkap dengan data mitra_bisnis
    protected function buatMitra(array $data = []): mitra_bisnisModel
    {
        $user = $this->buatUser('Mitra', $data);

        return mitra_bisnisModel::create([
            'user_id' => $user->user_id,
            'nama_mitra' => $user->nama,
            'alamat' => 'Jl. Test No. 1',
            'kontak' => $user->kontak,
            'email' => $user->email,
            'nama_bank' => 'BCA',
            'no_rekening' => '1234567890',
            'kategori' => 'Vendor Barang',
        ]);
    }

    protected function buatLaporan(userModel $pelapor, array $data = []): laporan_kerusakanModel
    {
        return laporan_kerusakanModel::create(array_merge([
            'tanggal_lapor' => now()->toDateString(),
            'lokasi_fasilitas' => 'Kamar 101',
            'deskripsi_kerusakan' => 'Lampu kamar mati',
            'status_laporan' => 'Menunggu',
            'foto_kerusakan' => 'foto_kerusakan/test.jpg',
            'user_id' => $pelapor->user_id,
        ], $data));
    }

    protected function buatBarang(mitra_bisnisModel $mitra, array $data = []): barang_jasaModel
    {
        return barang_jasaModel::create(array_merge([
            'mitra_id' => $mitra->mitra_id,
            'nama_barang' => 'Lampu LED',
            'deskripsi' => 'Lampu LED 12 watt',
            'jenis' => 'Barang',
            'stok' => 10,
            'harga_satuan' => 25000,
            'status_ketersediaan' => 'Tersedia',
            'foto_produk' => 'foto_produk/test.jpg',
        ], $data));
    }

    protected function buatPembelian(barang_jasaModel $barang, int $jumlah = 2, array $data = []): pembelianModel
    {
        return pembelianModel::create(array_merge([
            'mitra_id' => $barang->mitra_id,
            'barang_id' => $barang->barang_id,
            'nama_barang' => $barang->nama_barang,
            'jenis' => $barang->jenis,
            'jumlah_beli' => $jumlah,
            'total_harga' => $barang->harga_satuan * $jumlah,
        ], $data));
    }
}
