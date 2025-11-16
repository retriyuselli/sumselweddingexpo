<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (! Schema::hasColumn('homes', 'hero_bg_image_mobile')) {
                $table->string('hero_bg_image_mobile')->nullable()->after('hero_bg_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (Schema::hasColumn('homes', 'hero_bg_image_mobile')) {
                $table->dropColumn('hero_bg_image_mobile');
            }
        });
    }
};