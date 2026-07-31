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
            if (! Schema::hasColumn('vendors', 'paket')) {
                $table->string('paket')->nullable()->after('no_wa_pic');
            }
            if (! Schema::hasColumn('vendors', 'lokasi_booth')) {
                $table->string('lokasi_booth', 100)->nullable()->after('paket');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'lokasi_booth')) {
                $table->dropColumn('lokasi_booth');
            }
            if (Schema::hasColumn('vendors', 'paket')) {
                $table->dropColumn('paket');
            }
        });
    }
};
