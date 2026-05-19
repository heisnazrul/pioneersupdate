<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->string('region')->nullable();
            $table->string('ar_region')->nullable();
            $table->longText('description')->nullable();
            $table->longText('ar_description')->nullable();
            $table->string('image_url')->nullable();
            $table->text('short_pitch')->nullable();
            $table->text('ar_short_pitch')->nullable();
            $table->string('tuition_range')->nullable();
            $table->string('ar_tuition_range')->nullable();
            $table->string('visa_timeline')->nullable();
            $table->string('ar_visa_timeline')->nullable();
            $table->string('work_rights')->nullable();
            $table->string('ar_work_rights')->nullable();
            $table->string('scholarships_summary')->nullable();
            $table->string('ar_scholarships_summary')->nullable();
            $table->text('entry_req_gpa')->nullable();
            $table->text('ar_entry_req_gpa')->nullable();
            $table->text('entry_req_language')->nullable();
            $table->text('ar_entry_req_language')->nullable();
            $table->integer('university_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('destination_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('feature');
            $table->string('ar_feature')->nullable();
            $table->timestamps();
        });

        Schema::create('destination_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('label');
            $table->string('ar_label')->nullable();
            $table->string('value');
            $table->string('ar_value')->nullable();
            $table->timestamps();
        });

        Schema::create('destination_intakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('month');
            $table->string('ar_month')->nullable();
            $table->string('event');
            $table->string('ar_event')->nullable();
            $table->timestamps();
        });

        Schema::create('destination_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->text('question');
            $table->text('ar_question')->nullable();
            $table->text('answer');
            $table->text('ar_answer')->nullable();
            $table->timestamps();
        });

        Schema::create('destination_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('requirement');
            $table->string('ar_requirement')->nullable();
            $table->timestamps();
        });

        Schema::create('destination_disciplines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('discipline');
            $table->string('ar_discipline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_disciplines');
        Schema::dropIfExists('destination_requirements');
        Schema::dropIfExists('destination_faqs');
        Schema::dropIfExists('destination_intakes');
        Schema::dropIfExists('destination_stats');
        Schema::dropIfExists('destination_features');
        Schema::dropIfExists('destinations');
    }
};
