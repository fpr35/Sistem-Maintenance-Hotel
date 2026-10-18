<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Menghubungkan pembelian dengan laporan kerusakan yang ditangani,
    // sehingga alur lapor -> beli -> perbaikan bisa ditelusuri.
    public function up(): void
    {
        if (Schema::hasColumn('pembelian', 'laporan_id')) {
            return;
        }

        Schema::table('pembelian', function (Blueprint $table) {
            $table->integer('laporan_id')->nullable()->after('barang_id');
            $table->foreign('laporan_id')->references('laporan_id')->on('laporan_kerusakan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('pembelian', 'laporan_id')) {
            return;
        }

        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropForeign(['laporan_id']);
            $table->dropColumn('laporan_id');
        });
    }
};
