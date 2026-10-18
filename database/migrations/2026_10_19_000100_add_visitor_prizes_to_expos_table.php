<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expos', function (Blueprint $table) {
            $table->boolean('visitor_prizes_enabled')->default(false);
            $table->json('visitor_prizes')->nullable();
            $table->text('visitor_prize_terms')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('expos', function (Blueprint $table) {
            $table->dropColumn(['visitor_prizes_enabled', 'visitor_prizes', 'visitor_prize_terms']);
        });
    }
};
