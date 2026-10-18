<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Katalog barang/jasa yang dijual mitra
    public function up(): void
    {
        if (Schema::hasTable('barang_jasa')) {
            return;
        }

        Schema::create('barang_jasa', function (Blueprint $table) {
            $table->integer('barang_id', true);
            $table->integer('mitra_id');
            $table->string('nama_barang', 100);
            $table->text('deskripsi');
            $table->enum('jenis', ['Barang', 'Jasa']);
            $table->integer('stok')->nullable()->default(0);
            $table->decimal('harga_satuan', 12, 2)->nullable();
            $table->enum('status_ketersediaan', ['Tersedia', 'Habis'])->nullable()->default('Tersedia');
            $table->string('foto_produk', 255);
            $table->timestamps();

            $table->foreign('mitra_id')->references('mitra_id')->on('mitra_bisnis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_jasa');
    }
};
