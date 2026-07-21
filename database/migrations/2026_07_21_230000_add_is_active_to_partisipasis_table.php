<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('partisipasis', 'is_active')) {
            Schema::table('partisipasis', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('status_pembayaran');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('partisipasis', 'is_active')) {
            Schema::table('partisipasis', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
