<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doorprizes', function (Blueprint $table) {
            $table->string('email')->nullable()->after('no_wa');
            $table->string('provinsi')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('doorprizes', function (Blueprint $table) {
            $table->dropColumn(['email', 'provinsi']);
        });
    }
};

