<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'nama_pendaftar')) {
                $table->string('nama_pendaftar')->nullable()->after('nama_vendor');
            }
            if (! Schema::hasColumn('vendors', 'pendamping_tenant')) {
                $table->string('pendamping_tenant')->nullable()->after('no_telepon');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'pendamping_tenant')) {
                $table->dropColumn('pendamping_tenant');
            }
            if (Schema::hasColumn('vendors', 'nama_pendaftar')) {
                $table->dropColumn('nama_pendaftar');
            }
        });
    }
};
