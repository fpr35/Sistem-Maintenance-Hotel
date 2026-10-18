<?php

namespace Tests\Feature;

use App\Mail\NotifikasiAdminMail;
use App\Mail\ResetPasswordMail;
use App\Models\unverified_akun_mitraModel;
use App\Models\userModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_berhasil_dengan_username_dan_password_benar(): void
    {
        $user = $this->buatUser('Tamu', ['username' => 'tamu1']);

        $this->post('/loginProcess', ['username' => 'tamu1', 'password' => 'rahasia123'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $this->buatUser('Tamu', ['username' => 'tamu1']);

        $this->post('/loginProcess', ['username' => 'tamu1', 'password' => 'salah'])
            ->assertSessionHas('error');

        $this->assertGuest();
    }

    public function test_akun_nonaktif_tidak_bisa_login(): void
    {
        $this->buatUser('Tamu', ['username' => 'tamu1', 'aktif' => false]);

        $this->post('/loginProcess', ['username' => 'tamu1', 'password' => 'rahasia123'])
            ->assertSessionHas('error');

        $this->assertGuest();
    }

    public function test_register_berhasil_untuk_role_tamu(): void
    {
        $this->post('/registerProcess', $this->dataRegister())
            ->assertRedirect(route('login'));

        $user = userModel::where('username', 'baru')->first();
        $this->assertNotNull($user);
        $this->assertSame('Tamu', $user->role);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_register_sebagai_admin_ditolak(): void
    {
        $this->post('/registerProcess', $this->dataRegister(['role' => 'Admin']))
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('user', ['username' => 'baru']);
    }

    public function test_register_dengan_konfirmasi_password_berbeda_ditolak(): void
    {
        $this->post('/registerProcess', $this->dataRegister(['password_confirmation' => 'beda123']))
            ->assertSessionHasErrors('password');
    }

    public function test_register_dengan_kontak_bukan_angka_ditolak(): void
    {
        $this->post('/registerProcess', $this->dataRegister(['kontak' => 'abc']))
            ->assertSessionHasErrors('kontak');
    }

    public function test_register_mitra_menyimpan_password_ter_hash_dan_memberi_tahu_admin(): void
    {
        Mail::fake();
        $this->buatUser('Admin');

        $this->post('/registerProcess_mitra', [
            'nama' => 'CV Baru',
            'username' => 'cvbaru',
            'email' => 'cv@baru.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'alamat' => 'Jl. Baru',
            'kontak' => '081234567890',
            'nama_bank' => 'BRI',
            'no_rekening' => '1234567890',
            'kategori' => 'Vendor Jasa',
        ])->assertRedirect(route('login'));

        $mitra = unverified_akun_mitraModel::where('username', 'cvbaru')->firstOrFail();
        $this->assertNotSame('rahasia123', $mitra->password);
        $this->assertTrue(Hash::check('rahasia123', $mitra->password));
        $this->assertSame('Vendor Jasa', $mitra->kategori);

        Mail::assertSent(NotifikasiAdminMail::class);
    }

    public function test_reset_password_mengirim_email(): void
    {
        Mail::fake();
        $user = $this->buatUser('Tamu');

        $this->post('/resetPasswordProcess', ['email' => $user->email])
            ->assertRedirect(route('login'));

        Mail::assertQueued(ResetPasswordMail::class);
    }

    public function test_password_baru_dengan_token_palsu_ditolak(): void
    {
        $user = $this->buatUser('Admin');
        $hashLama = $user->password;

        $this->post('/new_password_process/' . $user->user_id, [
            'token' => 'token-palsu',
            'password' => 'diretas123',
            'password_confirmation' => 'diretas123',
        ])->assertRedirect(route('reset_password'));

        $this->assertSame($hashLama, $user->fresh()->password);
    }

    public function test_password_baru_dengan_token_asli_berhasil_dan_token_hangus(): void
    {
        $user = $this->buatUser('Tamu');
        $token = Password::createToken($user);

        $this->post('/new_password_process/' . $user->user_id, [
            'token' => $token,
            'password' => 'passwordbaru',
            'password_confirmation' => 'passwordbaru',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('passwordbaru', $user->fresh()->password));
        $this->assertFalse(Password::tokenExists($user, $token));
    }

    public function test_logout_harus_post(): void
    {
        $user = $this->buatUser('Tamu');

        $this->actingAs($user)->get('/logout')->assertStatus(405);
        $this->actingAs($user)->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    private function dataRegister(array $ubah = []): array
    {
        return array_merge([
            'nama' => 'User Baru',
            'username' => 'baru',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role' => 'Tamu',
            'kontak' => '081234567890',
            'email' => 'baru@test.local',
        ], $ubah);
    }
}
