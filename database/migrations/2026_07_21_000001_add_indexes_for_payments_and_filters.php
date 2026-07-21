<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['provider', 'external_id'], 'payments_provider_external_id_index');
        });

        if (Schema::hasTable('partisipasis')) {
            Schema::table('partisipasis', function (Blueprint $table) {
                $table->index(['expo_id', 'status_pembayaran'], 'partisipasis_expo_status_pembayaran_index');
                $table->index(['expo_id', 'sisa_pembayaran'], 'partisipasis_expo_sisa_pembayaran_index');
            });
        }

        if (Schema::hasTable('expos')) {
            Schema::table('expos', function (Blueprint $table) {
                $table->index(['status', 'tanggal_mulai'], 'expos_status_tanggal_mulai_index');
            });
        }

        if (Schema::hasTable('blogs')) {
            Schema::table('blogs', function (Blueprint $table) {
                if (Schema::hasColumn('blogs', 'is_published')) {
                    $table->index('is_published', 'blogs_is_published_index');
                }
            });
        }

        if (Schema::hasTable('product_vendors')) {
            Schema::table('product_vendors', function (Blueprint $table) {
                if (Schema::hasColumn('product_vendors', 'is_active')) {
                    $table->index(['vendor_id', 'is_active'], 'product_vendors_vendor_is_active_index');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_provider_external_id_index');
        });

        if (Schema::hasTable('partisipasis')) {
            Schema::table('partisipasis', function (Blueprint $table) {
                $table->dropIndex('partisipasis_expo_status_pembayaran_index');
                $table->dropIndex('partisipasis_expo_sisa_pembayaran_index');
            });
        }

        if (Schema::hasTable('expos')) {
            Schema::table('expos', function (Blueprint $table) {
                $table->dropIndex('expos_status_tanggal_mulai_index');
            });
        }

        if (Schema::hasTable('blogs') && Schema::hasColumn('blogs', 'is_published')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropIndex('blogs_is_published_index');
            });
        }

        if (Schema::hasTable('product_vendors') && Schema::hasColumn('product_vendors', 'is_active')) {
            Schema::table('product_vendors', function (Blueprint $table) {
                $table->dropIndex('product_vendors_vendor_is_active_index');
            });
        }
    }
};
