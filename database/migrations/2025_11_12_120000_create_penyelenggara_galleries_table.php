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
        Schema::create('penyelenggara_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyelenggara_id')->constrained('penyelenggaras')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('image_path');
            $table->string('photographer_name')->nullable();
            $table->date('photo_date')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('penyelenggara_id');
            $table->index('is_featured');
            $table->index('is_published');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyelenggara_galleries');
    }
};
