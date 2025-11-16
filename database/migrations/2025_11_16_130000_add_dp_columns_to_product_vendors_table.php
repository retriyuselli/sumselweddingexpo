<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('product_vendors', 'dp_percent')) {
                $table->unsignedTinyInteger('dp_percent')->nullable()->after('harga');
            }
            if (! Schema::hasColumn('product_vendors', 'dp_fixed')) {
                $table->unsignedBigInteger('dp_fixed')->nullable()->after('dp_percent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_vendors', function (Blueprint $table) {
            if (Schema::hasColumn('product_vendors', 'dp_fixed')) {
                $table->dropColumn('dp_fixed');
            }
            if (Schema::hasColumn('product_vendors', 'dp_percent')) {
                $table->dropColumn('dp_percent');
            }
        });
    }
};