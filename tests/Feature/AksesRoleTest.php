<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AksesRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_admin_bisa_dibuka_admin(): void
    {
        $admin = $this->buatUser('Admin');

        foreach ([
            '/dashboard', '/laporan_kerusakan_admin', '/list_barang_jasa', '/riwayat_pembelian',
            '/mitra_belum_terverifikasi', '/kelola_user', '/profil',
            '/pencarian_laporan?search=a', '/pencarian_barangjasa?search=a',
            '/pencarian_mitra_belum_verifikasi?search=a', '/pencarian_riwayat_pembelian?search=a',
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_halaman_mitra_bisa_dibuka_mitra(): void
    {
        $mitra = $this->buatMitra();
        $user = $mitra->user;

        foreach ([
            '/dashboard', '/barang_jasa', '/orderan', '/histori_orderan', '/laporan_kerusakan_mitra',
            '/tambah_barangjasa', '/profil',
            '/pencarian_barangjasa_mitra?search=a', '/pencarian_orderan?search=a',
            '/pencarian_histori_orderan?search=a', '/pencarian_laporan_kerusakan_mitra?search=a',
        ] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }

    public function test_halaman_tamu_bisa_dibuka_tamu_dan_pegawai(): void
    {
        foreach (['Tamu', 'Pegawai'] as $role) {
            $user = $this->buatUser($role);

            foreach (['/dashboard', '/laporan_kerusakan', '/tambah_laporan', '/profil', '/pencarian_laporan_kerusakan?search=a'] as $url) {
                $this->actingAs($user)->get($url)->assertOk();
            }
        }
    }

    public function test_role_lain_ditolak_masuk_halaman_admin(): void
    {
        $tamu = $this->buatUser('Tamu');
        $mitra = $this->buatMitra()->user;

        foreach (['/laporan_kerusakan_admin', '/kelola_user', '/rekap/laporan'] as $url) {
            $this->actingAs($tamu)->get($url)->assertRedirect('/login');
            $this->actingAs($mitra)->get($url)->assertRedirect('/login');
        }
    }

    public function test_admin_ditolak_masuk_halaman_mitra_dan_tamu(): void
    {
        $admin = $this->buatUser('Admin');

        $this->actingAs($admin)->get('/barang_jasa')->assertRedirect('/login');
        $this->actingAs($admin)->get('/laporan_kerusakan')->assertRedirect('/login');
    }

    public function test_tamu_belum_login_diarahkan_ke_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_mitra_tanpa_data_mitra_bisnis_dikeluarkan(): void
    {
        $user = $this->buatUser('Mitra');

        $this->actingAs($user)->get('/barang_jasa')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_user_yang_dinonaktifkan_langsung_dikeluarkan(): void
    {
        $user = $this->buatUser('Tamu');
        $this->actingAs($user)->get('/dashboard')->assertOk();

        $user->aktif = false;
        $user->save();

        $this->actingAs($user)->get('/dashboard')->assertRedirect('/login');
        $this->assertGuest();
    }
}
