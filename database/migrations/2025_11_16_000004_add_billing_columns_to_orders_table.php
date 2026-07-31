<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'billing_first_name')) {
                $table->string('billing_first_name')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('orders', 'billing_last_name')) {
                $table->string('billing_last_name')->nullable()->after('billing_first_name');
            }
            if (!Schema::hasColumn('orders', 'billing_company')) {
                $table->string('billing_company')->nullable()->after('billing_last_name');
            }
            if (!Schema::hasColumn('orders', 'billing_country')) {
                $table->string('billing_country')->nullable()->after('billing_company');
            }
            if (!Schema::hasColumn('orders', 'billing_street')) {
                $table->text('billing_street')->nullable()->after('billing_country');
            }
            if (!Schema::hasColumn('orders', 'billing_apt')) {
                $table->string('billing_apt')->nullable()->after('billing_street');
            }
            if (!Schema::hasColumn('orders', 'billing_city')) {
                $table->string('billing_city')->nullable()->after('billing_apt');
            }
            if (!Schema::hasColumn('orders', 'billing_province')) {
                $table->string('billing_province')->nullable()->after('billing_city');
            }
            if (!Schema::hasColumn('orders', 'billing_postcode')) {
                $table->string('billing_postcode')->nullable()->after('billing_province');
            }
            if (!Schema::hasColumn('orders', 'billing_phone')) {
                $table->string('billing_phone')->nullable()->after('billing_postcode');
            }
            if (!Schema::hasColumn('orders', 'billing_email')) {
                $table->string('billing_email')->nullable()->after('billing_phone');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('billing_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach ([
                'billing_first_name','billing_last_name','billing_company','billing_country','billing_street','billing_apt','billing_city','billing_province','billing_postcode','billing_phone','billing_email','notes'
            ] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};