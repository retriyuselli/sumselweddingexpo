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
        Schema::table('doorprizes', function (Blueprint $table) {
            $table->dropColumn(['nom_trx', 'no_rev']);
            $table->json('transactions')->nullable()->after('foto_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doorprizes', function (Blueprint $table) {
            $table->dropColumn('transactions');
            $table->decimal('nom_trx', 15, 2)->nullable()->after('foto_ktp');
            $table->decimal('no_rev', 15, 2)->nullable()->after('nom_trx');
        });
    }
};
