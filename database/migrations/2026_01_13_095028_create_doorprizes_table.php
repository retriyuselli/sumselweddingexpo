<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doorprizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kodevoucher')->unique();
            $table->string('no_wa');
            $table->string('nik')->nullable();
            $table->text('alamat');
            $table->boolean('sudah_download_tring')->default(false);
            $table->foreignId('partisipasi_id')->constrained('partisipasis')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doorprizes');
    }
};
