<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_school_branch_high_season_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('language_school_branches')->cascadeOnDelete();
            $table->unsignedSmallInteger('week_start');
            $table->unsignedSmallInteger('week_end')->nullable();
            $table->decimal('fee', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_branch_high_season_fees');
    }
};
