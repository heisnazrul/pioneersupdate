<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->timestamps();
        });

        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->timestamps();
        });

        Schema::create('bedroom_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->timestamps();
        });

        Schema::create('bathroom_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->timestamps();
        });

        Schema::create('language_school_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('language_school_branches')->cascadeOnDelete();
            $table->foreignId('accommodation_type_id')->constrained('accommodation_types')->restrictOnDelete();
            $table->string('name');
            $table->foreignId('bedroom_type_id')->nullable()->constrained('bedroom_types')->nullOnDelete();
            $table->foreignId('bathroom_type_id')->nullable()->constrained('bathroom_types')->nullOnDelete();
            $table->foreignId('meal_plan_id')->nullable()->constrained('meal_plans')->nullOnDelete();

            $table->decimal('weekly_fee', 10, 2)->default(0);
            $table->decimal('admin_fee', 10, 2)->default(0);
            $table->decimal('security_deposit', 10, 2)->default(0);
            $table->unsignedSmallInteger('min_age')->nullable();
            $table->decimal('under_18_supplement_fee', 10, 2)->default(0);

            $table->decimal('summer_supplement_fee', 10, 2)->default(0);
            $table->date('summer_start_date')->nullable();
            $table->date('summer_end_date')->nullable();

            $table->decimal('winter_supplement_fee', 10, 2)->default(0);
            $table->date('winter_start_date')->nullable();
            $table->date('winter_end_date')->nullable();

            $table->string('other_supplement_name')->nullable();
            $table->decimal('other_supplement_fee', 10, 2)->default(0);
            $table->date('other_start_date')->nullable();
            $table->date('other_end_date')->nullable();

            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_accommodations');
        Schema::dropIfExists('bathroom_types');
        Schema::dropIfExists('bedroom_types');
        Schema::dropIfExists('meal_plans');
        Schema::dropIfExists('accommodation_types');
    }
};
