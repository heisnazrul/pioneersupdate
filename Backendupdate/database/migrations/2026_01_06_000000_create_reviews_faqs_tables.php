<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->string('photo')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('ar_institute_name')->nullable();
            $table->string('title')->nullable();
            $table->string('ar_title')->nullable();
            $table->text('review_text')->nullable();
            $table->text('ar_review_text')->nullable();
            $table->string('gender')->nullable();
            $table->integer('rating')->default(5);
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->json('screenshots')->nullable();
            $table->string('video')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_iframe')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('university_name')->nullable();
            $table->string('course_name')->nullable();
            $table->string('country_name')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('ar_category')->nullable();
            $table->text('question');
            $table->text('ar_question')->nullable();
            $table->text('answer');
            $table->text('ar_answer')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('reviews');
    }
};
