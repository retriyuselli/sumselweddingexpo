<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE galleries MODIFY image_path JSON NULL');
        DB::statement('ALTER TABLE galleries DROP COLUMN description, DROP COLUMN photographer_name, DROP COLUMN photo_date, DROP COLUMN display_order, DROP COLUMN is_featured, DROP COLUMN is_published, DROP COLUMN tags');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE galleries MODIFY image_path VARCHAR(191) NULL');
        DB::statement('ALTER TABLE galleries ADD COLUMN description TEXT NULL, ADD COLUMN photographer_name VARCHAR(255) NULL, ADD COLUMN photo_date DATE NULL, ADD COLUMN display_order INT DEFAULT 0, ADD COLUMN is_featured TINYINT(1) DEFAULT 0, ADD COLUMN is_published TINYINT(1) DEFAULT 1, ADD COLUMN tags JSON NULL');
    }
};