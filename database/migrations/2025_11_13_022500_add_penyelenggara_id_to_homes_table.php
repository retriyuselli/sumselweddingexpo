<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (! Schema::hasColumn('homes', 'penyelenggara_id')) {
                $table->foreignId('penyelenggara_id')
                    ->nullable()
                    ->constrained('penyelenggaras')
                    ->nullOnDelete()
                    ->after('meta_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (Schema::hasColumn('homes', 'penyelenggara_id')) {
                $table->dropConstrainedForeignId('penyelenggara_id');
            }
        });
    }
};