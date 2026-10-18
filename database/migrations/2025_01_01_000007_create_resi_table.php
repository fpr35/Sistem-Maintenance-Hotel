<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Resi/bukti pengiriman untuk pembelian yang sudah dibayar
    public function up(): void
    {
        if (Schema::hasTable('resi')) {
            return;
        }

        Schema::create('resi', function (Blueprint $table) {
            $table->integer('resi_id', true);
            $table->integer('mitra_id');
            $table->integer('pembelian_id');
            $table->string('nama', 150);
            $table->string('alamat', 255);
            $table->char('kontak', 100);
            $table->timestamps();

            $table->index('pembelian_id');
            $table->index('mitra_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resi');
    }
};
