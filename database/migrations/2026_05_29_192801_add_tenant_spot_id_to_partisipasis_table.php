<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Column may already exist from a partial/manual schema sync.
        if (! Schema::hasColumn('partisipasis', 'tenant_spot_id')) {
            Schema::table('partisipasis', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_spot_id')
                    ->nullable()
                    ->after('category_tenant_id');
            });
        }

        // FK can only be added after tenant_spots exists (created by a later migration).
        if (
            Schema::hasTable('tenant_spots')
            && Schema::hasColumn('partisipasis', 'tenant_spot_id')
            && ! $this->foreignKeyExists('partisipasis', 'partisipasis_tenant_spot_id_foreign')
        ) {
            Schema::table('partisipasis', function (Blueprint $table) {
                $table->foreign('tenant_spot_id')
                    ->references('id')
                    ->on('tenant_spots')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('partisipasis', 'tenant_spot_id')) {
            return;
        }

        Schema::table('partisipasis', function (Blueprint $table) {
            if ($this->foreignKeyExists('partisipasis', 'partisipasis_tenant_spot_id_foreign')) {
                $table->dropForeign(['tenant_spot_id']);
            }
            $table->dropColumn('tenant_spot_id');
        });
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        $database = Schema::getConnection()->getDatabaseName();

        $result = Schema::getConnection()->selectOne(
            'select constraint_name
             from information_schema.table_constraints
             where table_schema = ?
               and table_name = ?
               and constraint_name = ?
               and constraint_type = ?',
            [$database, $table, $constraint, 'FOREIGN KEY']
        );

        return $result !== null;
    }
};
