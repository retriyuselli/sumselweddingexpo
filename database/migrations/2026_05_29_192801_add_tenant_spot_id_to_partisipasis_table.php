<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partisipasis', function (Blueprint $table) {
            $table->foreignId('tenant_spot_id')
                ->nullable()
                ->after('category_tenant_id')
                ->constrained('tenant_spots')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('partisipasis', function (Blueprint $table) {
            $table->dropForeign(['tenant_spot_id']);
            $table->dropColumn('tenant_spot_id');
        });
    }
};
