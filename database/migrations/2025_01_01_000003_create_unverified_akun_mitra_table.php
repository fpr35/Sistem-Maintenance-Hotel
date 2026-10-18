<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Antrean pendaftaran mitra yang menunggu verifikasi admin
    public function up(): void
    {
        if (Schema::hasTable('unverified_akun_mitra')) {
            return;
        }

        Schema::create('unverified_akun_mitra', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama', 100);
            $table->string('username', 50);
            $table->string('password', 100);
            $table->string('alamat', 255);
            $table->char('kontak', 100);
            $table->string('email', 100);
            $table->string('nama_bank', 100);
            $table->char('no_rekening', 100);
            $table->enum('kategori', ['Vendor Barang', 'Vendor Jasa']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unverified_akun_mitra');
    }
};
