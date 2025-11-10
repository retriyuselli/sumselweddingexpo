<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, convert existing data to JSON array format
        DB::table('galleries')->get()->each(function ($gallery) {
            if (!empty($gallery->image_path) && !str_starts_with($gallery->image_path, '[')) {
                DB::table('galleries')
                    ->where('id', $gallery->id)
                    ->update(['image_path' => json_encode([$gallery->image_path])]);
            }
        });

        // Then change column type to JSON
        Schema::table('galleries', function (Blueprint $table) {
            $table->json('image_path')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert JSON back to string (take first element)
        DB::table('galleries')->get()->each(function ($gallery) {
            $images = json_decode($gallery->image_path, true);
            if (is_array($images) && !empty($images)) {
                DB::table('galleries')
                    ->where('id', $gallery->id)
                    ->update(['image_path' => $images[0]]);
            }
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->string('image_path')->change();
        });
    }
};
