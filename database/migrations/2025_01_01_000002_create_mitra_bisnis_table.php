<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Data usaha milik akun mitra yang sudah diverifikasi admin
    public function up(): void
    {
        if (Schema::hasTable('mitra_bisnis')) {
            return;
        }

        Schema::create('mitra_bisnis', function (Blueprint $table) {
            $table->integer('mitra_id', true);
            $table->integer('user_id');
            $table->string('nama_mitra', 100);
            $table->string('alamat', 255)->nullable();
            $table->char('kontak', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nama_bank', 100);
            $table->char('no_rekening', 100);
            $table->enum('kategori', ['Vendor Barang', 'Vendor Jasa']);

            $table->foreign('user_id')->references('user_id')->on('user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_bisnis');
    }
};
