<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('vendors', 'harga_jual')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->unsignedBigInteger('harga_jual')->nullable()->after('lokasi_booth');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('vendors', 'harga_jual')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropColumn('harga_jual');
            });
        }
    }
};
