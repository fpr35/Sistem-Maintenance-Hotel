<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Status akun: 1 = aktif, 0 = dinonaktifkan admin (tidak bisa login)
    public function up(): void
    {
        if (Schema::hasColumn('user', 'aktif')) {
            return;
        }

        Schema::table('user', function (Blueprint $table) {
            $table->boolean('aktif')->default(true)->after('email');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('user', 'aktif')) {
            return;
        }

        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });
    }
};
