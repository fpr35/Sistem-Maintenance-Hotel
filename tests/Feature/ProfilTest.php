<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_bisa_mengubah_data_diri(): void
    {
        $tamu = $this->buatUser('Tamu');

        $this->actingAs($tamu)->put('/profil', [
            'nama' => 'Nama Baru',
            'kontak' => '089999999999',
            'email' => 'baru@test.local',
        ])->assertRedirect(route('profil'));

        $tamu->refresh();
        $this->assertSame('Nama Baru', $tamu->nama);
        $this->assertSame('baru@test.local', $tamu->email);
    }

    public function test_email_yang_dipakai_akun_lain_ditolak(): void
    {
        $this->buatUser('Tamu', ['email' => 'dipakai@test.local']);
        $tamu = $this->buatUser('Tamu');

        $this->actingAs($tamu)->put('/profil', [
            'nama' => 'X',
            'kontak' => '089999999999',
            'email' => 'dipakai@test.local',
        ])->assertSessionHasErrors('email');
    }

    public function test_mitra_bisa_mengubah_data_rekening(): void
    {
        $mitra = $this->buatMitra();

        $this->actingAs($mitra->user)->put('/profil', [
            'nama' => $mitra->user->nama,
            'kontak' => '081111111111',
            'email' => $mitra->user->email,
            'alamat' => 'Alamat Baru',
            'nama_bank' => 'Mandiri',
            'no_rekening' => '5555555555',
        ])->assertRedirect(route('profil'));

        $mitra->refresh();
        $this->assertSame('Mandiri', $mitra->nama_bank);
        $this->assertSame('081111111111', trim($mitra->kontak));
    }

    public function test_ganti_password_wajib_password_lama_yang_benar(): void
    {
        $tamu = $this->buatUser('Tamu');

        $this->actingAs($tamu)->put('/profil/password', [
            'password_lama' => 'salah',
            'password' => 'passwordbaru',
            'password_confirmation' => 'passwordbaru',
        ])->assertSessionHasErrors('password_lama');
        $this->assertTrue(Hash::check('rahasia123', $tamu->fresh()->password));

        $this->actingAs($tamu)->put('/profil/password', [
            'password_lama' => 'rahasia123',
            'password' => 'passwordbaru',
            'password_confirmation' => 'passwordbaru',
        ])->assertSessionHas('success');
        $this->assertTrue(Hash::check('passwordbaru', $tamu->fresh()->password));
    }
}
