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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expo_id')->nullable();
            $table->string('nama_pengeluaran');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('nominal');
            $table->date('tanggal');
            $table->foreignId('rekening_tujuan_id')->constrained('rekening_tujuans')->onDelete('cascade');
            $table->string('bukti_transfer')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('expo_id')->references('id')->on('expos')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
