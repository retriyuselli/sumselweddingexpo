<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doorprizes', function (Blueprint $table) {
            $table->string('surat_penyataan')->nullable()->after('foto_ktp');
        });
    }

    public function down(): void
    {
        Schema::table('doorprizes', function (Blueprint $table) {
            $table->dropColumn('surat_penyataan');
        });
    }
};

