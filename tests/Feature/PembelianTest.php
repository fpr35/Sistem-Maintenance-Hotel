<?php

namespace Tests\Feature;

use App\Models\pembelianModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembelianTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Mail::fake();
    }

    public function test_admin_membeli_untuk_laporan_dan_data_diambil_dari_database(): void
    {
        $admin = $this->buatUser('Admin');
        $mitra = $this->buatMitra();
        $mitraLain = $this->buatMitra();
        $barang = $this->buatBarang($mitra);
        $laporan = $this->buatLaporan($this->buatUser('Tamu'), ['status_laporan' => 'Diverifikasi']);

        $this->actingAs($admin)->post('/beli_barangjasa', [
            'barang_id' => $barang->barang_id,
            'jumlah_beli' => 3,
            'laporan_id' => $laporan->laporan_id,
            'mitra_id' => $mitraLain->mitra_id,   // mencoba memanipulasi mitra
            'nama_barang' => 'Nama Palsu',         // mencoba memanipulasi nama
        ])->assertRedirect();

        $pembelian = pembelianModel::firstOrFail();
        $this->assertSame($mitra->mitra_id, (int) $pembelian->mitra_id);
        $this->assertSame('Lampu LED', $pembelian->nama_barang);
        $this->assertSame($laporan->laporan_id, (int) $pembelian->laporan_id);
        $this->assertEquals(75000, $pembelian->total_harga);
    }

    public function test_jumlah_beli_melebihi_stok_ditolak(): void
    {
        $admin = $this->buatUser('Admin');
        $barang = $this->buatBarang($this->buatMitra(), ['stok' => 2]);

        $this->actingAs($admin)->post('/beli_barangjasa', ['barang_id' => $barang->barang_id, 'jumlah_beli' => 5])
            ->assertSessionHas('error');
        $this->actingAs($admin)->post('/beli_barangjasa', ['barang_id' => $barang->barang_id, 'jumlah_beli' => 0])
            ->assertSessionHasErrors('jumlah_beli');

        $this->assertSame(0, pembelianModel::count());
    }

    public function test_bayar_mengurangi_stok_dan_tidak_bisa_dibayar_dua_kali(): void
    {
        $admin = $this->buatUser('Admin');
        $barang = $this->buatBarang($this->buatMitra(), ['stok' => 5]);
        $pembelian = $this->buatPembelian($barang, 5);

        $this->actingAs($admin)->post('/simpan_resi', $this->dataResi($pembelian))
            ->assertSessionHas('success');

        $barang->refresh();
        $this->assertSame(0, (int) $barang->stok);
        $this->assertSame('Habis', $barang->status_ketersediaan);
        $this->assertNotNull($pembelian->fresh()->bukti_bayar);

        // Kirim ulang form resi → ditolak, stok tidak terpotong lagi
        $this->actingAs($admin)->post('/simpan_resi', $this->dataResi($pembelian))
            ->assertSessionHas('error');
        $this->assertSame(0, (int) $barang->fresh()->stok);
    }

    public function test_bayar_pembelian_untuk_laporan_mengubah_status_laporan_menjadi_diperbaiki(): void
    {
        $admin = $this->buatUser('Admin');
        $barang = $this->buatBarang($this->buatMitra());
        $laporan = $this->buatLaporan($this->buatUser('Tamu'), ['status_laporan' => 'Diverifikasi']);
        $pembelian = $this->buatPembelian($barang, 1, ['laporan_id' => $laporan->laporan_id]);

        // Belum dibayar → status laporan belum berubah
        $this->assertSame('Diverifikasi', $laporan->fresh()->status_laporan);

        $this->actingAs($admin)->post('/simpan_resi', $this->dataResi($pembelian))
            ->assertSessionHas('success', fn ($pesan) => str_contains($pesan, 'Diperbaiki'));

        $this->assertSame('Diperbaiki', $laporan->fresh()->status_laporan);
    }

    public function test_bayar_tidak_menurunkan_status_laporan_yang_sudah_selesai(): void
    {
        $admin = $this->buatUser('Admin');
        $barang = $this->buatBarang($this->buatMitra());
        $laporan = $this->buatLaporan($this->buatUser('Tamu'), ['status_laporan' => 'Selesai']);
        $pembelian = $this->buatPembelian($barang, 1, ['laporan_id' => $laporan->laporan_id]);

        $this->actingAs($admin)->post('/simpan_resi', $this->dataResi($pembelian))
            ->assertSessionHas('success', fn ($pesan) => !str_contains($pesan, 'Diperbaiki'));

        $this->assertSame('Selesai', $laporan->fresh()->status_laporan);
    }

    public function test_bayar_pembelian_tanpa_laporan_tidak_mengubah_laporan_apa_pun(): void
    {
        $admin = $this->buatUser('Admin');
        $laporan = $this->buatLaporan($this->buatUser('Tamu'), ['status_laporan' => 'Diverifikasi']);
        $pembelian = $this->buatPembelian($this->buatBarang($this->buatMitra()), 1);

        $this->actingAs($admin)->post('/simpan_resi', $this->dataResi($pembelian))->assertSessionHas('success');

        $this->assertSame('Diverifikasi', $laporan->fresh()->status_laporan);
    }

    public function test_pembelian_belum_bayar_bisa_dibatalkan_yang_sudah_bayar_tidak(): void
    {
        $admin = $this->buatUser('Admin');
        $barang = $this->buatBarang($this->buatMitra());
        $belumBayar = $this->buatPembelian($barang);
        $sudahBayar = $this->buatPembelian($barang, 1, ['bukti_bayar' => 'bukti_bayar/x.jpg', 'status_bayar' => 'Sudah Bayar']);

        $this->actingAs($admin)->delete('/batal_pembelian/' . $belumBayar->pembelian_id)->assertSessionHas('success');
        $this->actingAs($admin)->delete('/batal_pembelian/' . $sudahBayar->pembelian_id)->assertSessionHas('error');

        $this->assertDatabaseMissing('pembelian', ['pembelian_id' => $belumBayar->pembelian_id]);
        $this->assertDatabaseHas('pembelian', ['pembelian_id' => $sudahBayar->pembelian_id]);
    }

    public function test_mitra_tidak_bisa_mengubah_pesanan_atau_barang_mitra_lain(): void
    {
        $mitraA = $this->buatMitra();
        $mitraB = $this->buatMitra();
        $barangB = $this->buatBarang($mitraB);
        $pesananB = $this->buatPembelian($barangB);

        $this->actingAs($mitraA->user)->put('/ubah_status_pembelian/' . $pesananB->pembelian_id, ['status_pembelian' => 'Selesai'])
            ->assertRedirect(route('orderan'));
        $this->actingAs($mitraA->user)->delete('/hapus_barangjasa/' . $barangB->barang_id)
            ->assertRedirect(route('barang_jasa'));

        $this->assertSame('Menunggu', $pesananB->fresh()->status_pembelian);
        $this->assertDatabaseHas('barang_jasa', ['barang_id' => $barangB->barang_id]);
    }

    public function test_mitra_bisa_mengubah_status_pesanan_miliknya(): void
    {
        $mitra = $this->buatMitra();
        $pesanan = $this->buatPembelian($this->buatBarang($mitra));

        $this->actingAs($mitra->user)->put('/ubah_status_pembelian/' . $pesanan->pembelian_id, ['status_pembelian' => 'Diproses'])
            ->assertSessionHas('success');

        $this->assertSame('Diproses', $pesanan->fresh()->status_pembelian);
    }

    public function test_riwayat_pembelian_menampilkan_laporan_terkait(): void
    {
        $admin = $this->buatUser('Admin');
        $laporan = $this->buatLaporan($this->buatUser('Tamu'), ['lokasi_fasilitas' => 'Kolam Renang']);
        $this->buatPembelian($this->buatBarang($this->buatMitra()), 1, ['laporan_id' => $laporan->laporan_id]);

        $this->actingAs($admin)->get('/riwayat_pembelian')->assertOk()->assertSee('Kolam Renang');
        $this->actingAs($admin)->get('/detail_laporan/' . $laporan->laporan_id)->assertOk()->assertSee('Lampu LED');
    }

    private function dataResi(pembelianModel $pembelian): array
    {
        return [
            'mitra_id' => $pembelian->mitra_id,
            'pembelian_id' => $pembelian->pembelian_id,
            'nama' => 'Admin Hotel',
            'alamat' => 'Jl. Hotel No. 1',
            'kontak' => '081234567890',
            'bukti_bayar' => UploadedFile::fake()->image('bukti.jpg'),
        ];
    }
}
