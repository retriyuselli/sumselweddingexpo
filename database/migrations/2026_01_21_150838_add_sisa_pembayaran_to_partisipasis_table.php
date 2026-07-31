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
        Schema::table('partisipasis', function (Blueprint $table) {
            $table->integer('sisa_pembayaran')->default(0)->after('total_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partisipasis', function (Blueprint $table) {
            $table->dropColumn('sisa_pembayaran');
        });
    }
};
