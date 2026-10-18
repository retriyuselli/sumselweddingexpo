<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expos', function (Blueprint $table) {
            $table->time('jam_buka_absensi')->default('09:00:00')->after('tanggal_selesai');
            $table->time('jam_tutup_absensi')->default('22:00:00')->after('jam_buka_absensi');
        });
    }

    public function down(): void
    {
        Schema::table('expos', function (Blueprint $table) {
            $table->dropColumn(['jam_buka_absensi', 'jam_tutup_absensi']);
        });
    }
};
