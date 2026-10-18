<?php

namespace Tests\Feature;

use App\Mail\NotifikasiAdminMail;
use App\Models\laporan_kerusakanModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanKerusakanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Mail::fake();
    }

    public function test_tamu_membuat_laporan_selalu_berstatus_menunggu_dan_admin_diberi_tahu(): void
    {
        $this->buatUser('Admin');
        $tamu = $this->buatUser('Tamu');

        $this->actingAs($tamu)->post('/simpan_laporan', [
            'tanggal_lapor' => now()->toDateString(),
            'lokasi_fasilitas' => 'Lobby',
            'deskripsi_kerusakan' => 'Pintu rusak',
            'status_laporan' => 'Selesai', // mencoba memanipulasi status
            'foto_kerusakan' => UploadedFile::fake()->image('kerusakan.jpg'),
        ])->assertRedirect(route('dashboard'));

        $laporan = laporan_kerusakanModel::where('lokasi_fasilitas', 'Lobby')->firstOrFail();
        $this->assertSame('Menunggu', $laporan->status_laporan);
        $this->assertSame($tamu->user_id, (int) $laporan->user_id);
        Storage::disk('public')->assertExists($laporan->foto_kerusakan);
        Mail::assertSent(NotifikasiAdminMail::class);
    }

    public function test_tanggal_lapor_di_masa_depan_ditolak(): void
    {
        $tamu = $this->buatUser('Tamu');

        $this->actingAs($tamu)->post('/simpan_laporan', [
            'tanggal_lapor' => now()->addDays(3)->toDateString(),
            'lokasi_fasilitas' => 'Lobby',
            'deskripsi_kerusakan' => 'Pintu rusak',
            'foto_kerusakan' => UploadedFile::fake()->image('kerusakan.jpg'),
        ])->assertSessionHasErrors('tanggal_lapor');
    }

    public function test_tidak_bisa_mengubah_atau_menghapus_laporan_milik_orang_lain(): void
    {
        $pemilik = $this->buatUser('Tamu');
        $penyusup = $this->buatUser('Pegawai');
        $laporan = $this->buatLaporan($pemilik);

        $this->actingAs($penyusup)->get('/edit_laporan/' . $laporan->laporan_id)->assertRedirect(route('laporan_kerusakan'));

        $this->actingAs($penyusup)->put('/update_laporan/' . $laporan->laporan_id, [
            'tanggal_lapor' => now()->toDateString(),
            'lokasi_fasilitas' => 'Diubah penyusup',
            'deskripsi_kerusakan' => 'x',
        ])->assertRedirect(route('laporan_kerusakan'));

        $this->actingAs($penyusup)->delete('/hapus_laporan/' . $laporan->laporan_id)->assertRedirect(route('laporan_kerusakan'));

        $laporan->refresh();
        $this->assertSame('Kamar 101', $laporan->lokasi_fasilitas);
        $this->assertSame($pemilik->user_id, (int) $laporan->user_id);
    }

    public function test_pemilik_bisa_mengubah_laporan_yang_masih_menunggu(): void
    {
        $tamu = $this->buatUser('Tamu');
        $laporan = $this->buatLaporan($tamu);

        $this->actingAs($tamu)->put('/update_laporan/' . $laporan->laporan_id, [
            'tanggal_lapor' => now()->toDateString(),
            'lokasi_fasilitas' => 'Kamar 102',
            'deskripsi_kerusakan' => 'Lampu mati total',
            'status_laporan' => 'Selesai',
        ])->assertRedirect(route('dashboard'));

        $laporan->refresh();
        $this->assertSame('Kamar 102', $laporan->lokasi_fasilitas);
        $this->assertSame('Menunggu', $laporan->status_laporan); // status tidak ikut berubah
    }

    public function test_laporan_yang_sudah_diproses_tidak_bisa_dihapus_pelapor(): void
    {
        $tamu = $this->buatUser('Tamu');
        $laporan = $this->buatLaporan($tamu, ['status_laporan' => 'Diverifikasi']);

        $this->actingAs($tamu)->delete('/hapus_laporan/' . $laporan->laporan_id)
            ->assertSessionHas('error');

        $this->assertDatabaseHas('laporan_kerusakan', ['laporan_id' => $laporan->laporan_id]);
    }

    public function test_pencarian_tidak_menampilkan_laporan_milik_orang_lain(): void
    {
        $saya = $this->buatUser('Tamu');
        $orangLain = $this->buatUser('Tamu');
        $this->buatLaporan($saya, ['lokasi_fasilitas' => 'Kamar Saya']);
        $this->buatLaporan($orangLain, ['lokasi_fasilitas' => 'Kamar Orang Lain', 'status_laporan' => 'Menunggu']);

        $this->actingAs($saya)->get('/pencarian_laporan_kerusakan?search=Menunggu')
            ->assertOk()
            ->assertViewHas('laporan_riwayat', function ($hasil) use ($saya) {
                return $hasil->count() === 1 && $hasil->every(fn ($l) => (int) $l->user_id === $saya->user_id);
            });
    }

    public function test_detail_laporan_sesuai_hak_akses(): void
    {
        $pemilik = $this->buatUser('Tamu', ['nama' => 'Budi Pelapor']);
        $tamuLain = $this->buatUser('Tamu');
        $admin = $this->buatUser('Admin');
        $mitra = $this->buatMitra()->user;
        $laporan = $this->buatLaporan($pemilik);
        $url = '/detail_laporan/' . $laporan->laporan_id;

        $this->actingAs($pemilik)->get($url)->assertOk();
        $this->actingAs($mitra)->get($url)->assertOk();
        $this->actingAs($admin)->get($url)->assertOk()->assertSee('Budi Pelapor');
        $this->actingAs($tamuLain)->get($url)->assertRedirect(route('laporan_kerusakan'));
    }

    public function test_admin_melihat_nama_pelapor_dan_bisa_mencari_berdasarkan_nama(): void
    {
        $admin = $this->buatUser('Admin');
        $pelapor = $this->buatUser('Pegawai', ['nama' => 'Siti Pegawai']);
        $this->buatLaporan($pelapor);

        $this->actingAs($admin)->get('/laporan_kerusakan_admin')->assertOk()->assertSee('Siti Pegawai');
        $this->actingAs($admin)->get('/pencarian_laporan?search=Siti')
            ->assertOk()
            ->assertViewHas('laporan_all', fn ($hasil) => $hasil->count() === 1);
    }

    public function test_admin_hanya_bisa_memakai_status_yang_valid(): void
    {
        $admin = $this->buatUser('Admin');
        $laporan = $this->buatLaporan($this->buatUser('Tamu'));

        $this->actingAs($admin)->put('/ubah_status_laporan/' . $laporan->laporan_id, ['status_laporan' => 'Ngawur'])
            ->assertSessionHas('error');
        $this->actingAs($admin)->put('/ubah_status_laporan/' . $laporan->laporan_id, ['status_laporan' => 'Diperbaiki'])
            ->assertSessionHas('success');

        $this->assertSame('Diperbaiki', $laporan->fresh()->status_laporan);
    }
}
