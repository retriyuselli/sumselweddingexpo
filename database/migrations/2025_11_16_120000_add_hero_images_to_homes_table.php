<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (! Schema::hasColumn('homes', 'hero_bg_image')) {
                $table->string('hero_bg_image')->nullable()->after('meta_description');
            }
            if (! Schema::hasColumn('homes', 'hero_side_image')) {
                $table->string('hero_side_image')->nullable()->after('hero_bg_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (Schema::hasColumn('homes', 'hero_side_image')) {
                $table->dropColumn('hero_side_image');
            }
            if (Schema::hasColumn('homes', 'hero_bg_image')) {
                $table->dropColumn('hero_bg_image');
            }
        });
    }
};