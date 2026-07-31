<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Tambahkan unique index pada user_id untuk memastikan satu user satu vendor
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        try {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropUnique('vendors_user_id_unique');
            });
        } catch (\Throwable $e) {
            // Biarkan jika index tidak bisa di-drop karena terikat foreign key.
            // Index akan ikut terhapus ketika tabel `vendors` di-drop oleh
            // migration create_vendors_table saat rollback penuh.
        }
    }
};
