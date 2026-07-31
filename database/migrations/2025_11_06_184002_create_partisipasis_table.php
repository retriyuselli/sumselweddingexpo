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
        Schema::create('partisipasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expo_id')->constrained('expos')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('vendor_pendamping')->nullable();
            $table->date('tanggal_booking');
            $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas', 'DP', 'Cicilan'])->default('Belum Lunas');
            $table->foreignId('category_tenant_id')->constrained('category_tenants')->onDelete('cascade');
            $table->string('blok_tenant')->nullable();
            $table->string('harga_jual')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partisipasis');
    }
};
