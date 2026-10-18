<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tabel akun login semua role (Admin, Pegawai, Tamu, Mitra).
    // hasTable() -> aman dijalankan di database lama hasil import .sql
    public function up(): void
    {
        if (Schema::hasTable('user')) {
            return;
        }

        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('nama', 100);
            $table->string('username', 50)->unique();
            $table->text('password');
            $table->enum('role', ['Pegawai', 'Tamu', 'Admin', 'Mitra']);
            $table->string('kontak', 100)->nullable();
            $table->string('email', 225);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
