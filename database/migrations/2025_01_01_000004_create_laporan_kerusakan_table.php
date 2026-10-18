<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Laporan kerusakan fasilitas yang dibuat Tamu/Pegawai
    public function up(): void
    {
        if (Schema::hasTable('laporan_kerusakan')) {
            return;
        }

        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->integer('laporan_id', true);
            $table->date('tanggal_lapor');
            $table->string('lokasi_fasilitas', 150)->nullable();
            $table->text('deskripsi_kerusakan')->nullable();
            $table->enum('status_laporan', ['Menunggu', 'Diverifikasi', 'Diperbaiki', 'Selesai'])->default('Menunggu');
            $table->string('foto_kerusakan', 255);
            $table->integer('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
};
