<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('language_school_courses', function (Blueprint $table): void {
            $table->dropUnique(['slug']);
            $table->string('slug', 160)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('language_school_courses', function (Blueprint $table): void {
            $table->string('slug', 160)->nullable(false)->change();
            $table->unique('slug');
        });
    }
};
