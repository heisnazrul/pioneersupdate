<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('language_schools', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->string('slug')->unique();
            $table->text('about_en')->nullable();
            $table->text('about_ar')->nullable();
            $table->text('logo_url')->nullable();
            $table->enum('has_online', ['yes', 'no'])->default('no');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('language_school_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
                ->constrained('language_schools')
                ->cascadeOnDelete();
            $table->foreignId('city_id')
                ->constrained('cities')
                ->restrictOnDelete();
            $table->string('slug');
            $table->date('new_year_close_from')->nullable();
            $table->date('new_year_close_to')->nullable();
            $table->json('branch_images')->nullable();
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();
            $table->unique(['school_id', 'city_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_branches');
        Schema::dropIfExists('language_schools');
    }
};
