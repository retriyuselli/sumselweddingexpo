<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluaran_lains', function (Blueprint $table) {
            if (! Schema::hasColumn('pengeluaran_lains', 'nota_dinas')) {
                $table->string('nota_dinas')->nullable()->after('bukti_transfer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengeluaran_lains', function (Blueprint $table) {
            if (Schema::hasColumn('pengeluaran_lains', 'nota_dinas')) {
                $table->dropColumn('nota_dinas');
            }
        });
    }
};
