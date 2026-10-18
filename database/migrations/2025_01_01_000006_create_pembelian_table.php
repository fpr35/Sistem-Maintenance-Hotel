<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Transaksi pembelian barang/jasa oleh admin ke mitra
    public function up(): void
    {
        if (Schema::hasTable('pembelian')) {
            return;
        }

        Schema::create('pembelian', function (Blueprint $table) {
            $table->integer('pembelian_id', true);
            $table->integer('mitra_id');
            $table->integer('barang_id');
            $table->integer('jumlah_beli');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status_pembelian', ['Menunggu', 'Diproses', 'Selesai'])->nullable()->default('Menunggu');
            $table->enum('status_bayar', ['Sudah Bayar', 'Belum Bayar'])->nullable()->default('Belum Bayar');
            $table->string('bukti_bayar', 255)->nullable();
            $table->string('nama_barang', 255);
            $table->string('jenis', 100);
            $table->timestamps();

            $table->foreign('mitra_id')->references('mitra_id')->on('mitra_bisnis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
