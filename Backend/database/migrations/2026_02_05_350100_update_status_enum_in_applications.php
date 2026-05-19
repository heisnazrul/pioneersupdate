<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Expand status values to include submitted and keep draft for existing data.
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('draft','pending','submitted','reviewing','contacted','accepted','rejected','invalid') DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('draft','pending','reviewing','contacted','accepted','rejected','invalid') DEFAULT 'draft'");
    }
};
