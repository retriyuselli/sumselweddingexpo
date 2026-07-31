<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure image_path is JSON and Nullable
        try {
            DB::statement('ALTER TABLE galleries MODIFY image_path JSON NULL');
        } catch (\Exception $e) {
            // Ignore if modification fails, likely already correct or driver issue
        }

        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'description')) $table->dropColumn('description');
            if (Schema::hasColumn('galleries', 'photographer_name')) $table->dropColumn('photographer_name');
            if (Schema::hasColumn('galleries', 'photo_date')) $table->dropColumn('photo_date');
            if (Schema::hasColumn('galleries', 'display_order')) $table->dropColumn('display_order');
            if (Schema::hasColumn('galleries', 'is_featured')) $table->dropColumn('is_featured');
            if (Schema::hasColumn('galleries', 'is_published')) $table->dropColumn('is_published');
            if (Schema::hasColumn('galleries', 'tags')) $table->dropColumn('tags');
        });
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE galleries MODIFY image_path VARCHAR(191) NULL');
        } catch (\Exception $e) {}

        Schema::table('galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('galleries', 'description')) $table->text('description')->nullable();
            if (!Schema::hasColumn('galleries', 'photographer_name')) $table->string('photographer_name')->nullable();
            if (!Schema::hasColumn('galleries', 'photo_date')) $table->date('photo_date')->nullable();
            if (!Schema::hasColumn('galleries', 'display_order')) $table->integer('display_order')->default(0);
            if (!Schema::hasColumn('galleries', 'is_featured')) $table->boolean('is_featured')->default(0);
            if (!Schema::hasColumn('galleries', 'is_published')) $table->boolean('is_published')->default(1);
            if (!Schema::hasColumn('galleries', 'tags')) $table->json('tags')->nullable();
        });
    }
};
