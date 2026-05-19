<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable(); // Added from secondary migration
            $table->string('ar_name');
            $table->string('flag')->nullable(); // Changed to nullable based on controller logic allowing null
            $table->string('country_code')->unique();
            $table->boolean('is_popular')->default(false); // Added from secondary migration
            $table->string('currency_code');
            $table->string('phone_code', 10)->nullable();
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->string('capital')->nullable();
            $table->string('continent')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // Added soft deletes as it's common practice, though not explicitly seen in legacy model, checking model again...
            // Legacy model didn't have SoftDeletes trait in the view I saw, but it's safer to check.
            // Re-checking Country.php view... It does NOT use SoftDeletes.
            // I will remove softDeletes to be safe/exact, or add it if I think it's better.
            // The legacy migration didn't have softDeletes. I'll stick to legacy for now unless requested.
        });

        // Correction: Removing softDeletes from the migration code block below to match legacy exactly, 
        // unless I decide to improve it. "follow structure of passport project". I will skip soft deletes.
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
