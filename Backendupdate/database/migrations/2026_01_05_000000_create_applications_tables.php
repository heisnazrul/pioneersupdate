<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique()->nullable();
            // Personal details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('citizenship')->nullable();
            $table->string('nationality')->nullable();
            $table->string('nationality_other')->nullable();
            // Academic profile
            $table->string('highest_education')->nullable();
            $table->string('grade_average')->nullable();
            $table->boolean('has_english_test')->default(false);
            $table->string('english_test_type')->nullable();
            $table->string('english_test_score')->nullable();
            // Preferences
            $table->json('destination_interest')->nullable();
            $table->string('destinations_other')->nullable();
            $table->string('preferred_intake')->nullable();
            $table->string('budget_range')->nullable();
            // Workflow
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('assigned_role')->nullable();
            $table->text('status_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
        Schema::dropIfExists('applications');
    }
};
