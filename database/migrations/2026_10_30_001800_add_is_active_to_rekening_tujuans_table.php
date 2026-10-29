<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('rekening_tujuans', 'is_active')) {
            Schema::table('rekening_tujuans', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('nama_pemilik');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('rekening_tujuans', 'is_active')) {
            Schema::table('rekening_tujuans', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
