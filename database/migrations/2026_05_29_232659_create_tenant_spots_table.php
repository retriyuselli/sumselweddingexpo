<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expo_id')->constrained('expos')->onDelete('cascade');
            $table->string('kode_booth');             // e.g. "A-01", "B-11"
            $table->string('blok', 5);                // e.g. "A", "B", "C"
            $table->unsignedSmallInteger('nomor');    // e.g. 1, 11
            $table->string('section')->nullable();    // e.g. "kiri" / "kanan" (for split blocks like B)
            $table->unsignedSmallInteger('baris');   // row within the section grid
            $table->unsignedSmallInteger('kolom');   // column within the section grid
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['expo_id', 'kode_booth']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_spots');
    }
};
