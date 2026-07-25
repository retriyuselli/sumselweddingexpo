<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('doorprizes')) {
            return;
        }

        if (Schema::hasColumn('doorprizes', 'surat_penyataan') && ! Schema::hasColumn('doorprizes', 'surat_pernyataan')) {
            Schema::table('doorprizes', function (Blueprint $table) {
                $table->string('surat_pernyataan')->nullable()->after('foto_ktp');
            });

            DB::table('doorprizes')->update([
                'surat_pernyataan' => DB::raw('surat_penyataan'),
            ]);

            Schema::table('doorprizes', function (Blueprint $table) {
                $table->dropColumn('surat_penyataan');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('doorprizes')) {
            return;
        }

        if (Schema::hasColumn('doorprizes', 'surat_pernyataan') && ! Schema::hasColumn('doorprizes', 'surat_penyataan')) {
            Schema::table('doorprizes', function (Blueprint $table) {
                $table->string('surat_penyataan')->nullable()->after('foto_ktp');
            });

            DB::table('doorprizes')->update([
                'surat_penyataan' => DB::raw('surat_pernyataan'),
            ]);

            Schema::table('doorprizes', function (Blueprint $table) {
                $table->dropColumn('surat_pernyataan');
            });
        }
    }
};
