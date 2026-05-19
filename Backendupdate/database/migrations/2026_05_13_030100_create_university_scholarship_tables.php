<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->nullable()->constrained('universities')->cascadeOnDelete();
            $table->string('provider_name')->nullable();
            $table->string('ar_provider_name')->nullable();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->string('slug');
            $table->string('summary')->nullable();
            $table->string('ar_summary')->nullable();
            $table->longText('description')->nullable();
            $table->longText('ar_description')->nullable();
            $table->enum('amount_type', ['fixed', 'percentage', 'variable'])->default('variable');
            $table->decimal('amount_value', 12, 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->decimal('min_amount', 12, 2)->nullable();
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->date('deadline_date')->nullable();
            $table->json('tags')->nullable();
            $table->json('eligible_nationalities')->nullable();
            $table->longText('eligibility_text')->nullable();
            $table->longText('ar_eligibility_text')->nullable();
            $table->string('apply_link', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['university_id', 'slug']);
            $table->index(['university_id', 'is_active']);
            $table->index('deadline_date');
        });

        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('scholarship_id')->nullable();
            $table->string('scholarship_title')->nullable();
            $table->string('scholarship_slug')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('education_level')->nullable();
            $table->string('grade_average')->nullable();
            $table->string('english_proficiency')->nullable();
            $table->enum('status', ['pending', 'reviewing', 'approved', 'rejected'])->default('pending');
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_applications');
        Schema::dropIfExists('scholarships');
    }
};
