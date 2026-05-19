<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('language_schools', function (Blueprint $table): void {
            $table->boolean('is_preferred')->default(false)->after('rating')->index();
        });
    }

    public function down(): void
    {
        Schema::table('language_schools', function (Blueprint $table): void {
            $table->dropColumn('is_preferred');
        });
    }
};
