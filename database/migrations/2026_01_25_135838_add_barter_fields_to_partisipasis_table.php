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
            $table->boolean('is_barter')->default(false)->after('keterangan');
            $table->text('barter_description')->nullable()->after('is_barter');
            $table->bigInteger('barter_nominal')->default(0)->nullable()->after('barter_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partisipasis', function (Blueprint $table) {
            $table->dropColumn(['is_barter', 'barter_description', 'barter_nominal']);
        });
    }
};
