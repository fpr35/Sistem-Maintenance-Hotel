<?php

namespace Tests\Feature;

use App\Mail\VerifikasiAkunMitraMail;
use App\Models\mitra_bisnisModel;
use App\Models\unverified_akun_mitraModel;
use App\Models\userModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class AdminFiturTest extends TestCase
{
    use RefreshDatabase;

    public function test_verifikasi_mitra_membuat_akun_yang_bisa_login(): void
    {
        Mail::fake();
        $admin = $this->buatUser('Admin');
        $calon = unverified_akun_mitraModel::create([
            'nama' => 'CV Calon',
            'username' => 'cvcalon',
            'password' => Hash::make('rahasia123'),
            'alamat' => 'Jl. Calon',
            'kontak' => '081234567890',
            'email' => 'calon@test.local',
            'nama_bank' => 'BNI',
            'no_rekening' => '9876543210',
            'kategori' => 'Vendor Jasa',
        ]);

        $this->actingAs($admin)->post('/verifikasi_akun/' . $calon->id)->assertSessionHas('success');

        $user = userModel::where('username', 'cvcalon')->firstOrFail();
        $this->assertSame('Mitra', $user->role);
        $this->assertSame('Vendor Jasa', mitra_bisnisModel::where('user_id', $user->user_id)->value('kategori'));
        $this->assertDatabaseMissing('unverified_akun_mitra', ['id' => $calon->id]);
        Mail::assertSent(VerifikasiAkunMitraMail::class);

        auth()->logout();
        $this->post('/loginProcess', ['username' => 'cvcalon', 'password' => 'rahasia123'])->assertRedirect('/dashboard');
    }

    public function test_admin_bisa_menonaktifkan_dan_mengaktifkan_user(): void
    {
        $admin = $this->buatUser('Admin');
        $tamu = $this->buatUser('Tamu');

        $this->actingAs($admin)->put('/kelola_user/' . $tamu->user_id . '/status')->assertSessionHas('success');
        $this->assertFalse($tamu->fresh()->aktif);

        $this->actingAs($admin)->put('/kelola_user/' . $tamu->user_id . '/status')->assertSessionHas('success');
        $this->assertTrue($tamu->fresh()->aktif);
    }

    public function test_akun_admin_tidak_bisa_dinonaktifkan(): void
    {
        $admin = $this->buatUser('Admin');
        $adminLain = $this->buatUser('Admin');

        $this->actingAs($admin)->put('/kelola_user/' . $adminLain->user_id . '/status')->assertSessionHas('error');
        $this->assertTrue($adminLain->fresh()->aktif);
    }

    public function test_kelola_user_bisa_dicari_dan_difilter(): void
    {
        $admin = $this->buatUser('Admin');
        $this->buatUser('Tamu', ['nama' => 'Andi Tamu']);
        $this->buatUser('Pegawai', ['nama' => 'Andi Pegawai']);

        $this->actingAs($admin)->get('/kelola_user?search=Andi&role=Pegawai')
            ->assertOk()
            ->assertViewHas('users', fn ($users) => $users->count() === 1 && $users->first()->nama === 'Andi Pegawai');
    }

    public function test_rekap_laporan_dan_pembelian_menghasilkan_pdf(): void
    {
        $admin = $this->buatUser('Admin');
        $laporan = $this->buatLaporan($this->buatUser('Tamu'));
        $this->buatPembelian($this->buatBarang($this->buatMitra()), 1, ['laporan_id' => $laporan->laporan_id]);
        $rentang = '?dari=' . now()->startOfMonth()->toDateString() . '&sampai=' . now()->toDateString();

        $this->actingAs($admin)->get('/rekap/laporan' . $rentang)
            ->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->actingAs($admin)->get('/rekap/pembelian' . $rentang)
            ->assertOk()->assertHeader('content-type', 'application/pdf');

        // Tanpa parameter → default tanggal 1 bulan ini s/d hari ini
        $this->actingAs($admin)->get('/rekap/laporan')
            ->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    public function test_rekap_hanya_memuat_data_dalam_rentang_tanggal(): void
    {
        $admin = $this->buatUser('Admin');
        $pelapor = $this->buatUser('Tamu');
        $this->buatLaporan($pelapor, ['lokasi_fasilitas' => 'Di Dalam Rentang', 'tanggal_lapor' => '2026-03-10']);
        $this->buatLaporan($pelapor, ['lokasi_fasilitas' => 'Di Luar Rentang', 'tanggal_lapor' => '2026-05-01']);

        // Isi PDF terkompresi, jadi tangkap data yang dikirim ke view PDF
        $lokasi = null;
        View::composer('admin.print.rekap_laporan', function ($view) use (&$lokasi) {
            $lokasi = $view->getData()['laporan_all']->pluck('lokasi_fasilitas')->all();
        });

        $this->actingAs($admin)->get('/rekap/laporan?dari=2026-03-01&sampai=2026-03-31')->assertOk();

        $this->assertSame(['Di Dalam Rentang'], $lokasi);
    }

    public function test_rekap_dengan_tanggal_akhir_sebelum_tanggal_awal_ditolak(): void
    {
        $admin = $this->buatUser('Admin');

        $this->actingAs($admin)
            ->from('/laporan_kerusakan_admin')
            ->get('/rekap/laporan?dari=2026-03-31&sampai=2026-03-01')
            ->assertRedirect('/laporan_kerusakan_admin')
            ->assertSessionHasErrors('sampai');
    }
}
