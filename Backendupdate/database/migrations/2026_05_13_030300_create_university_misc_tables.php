<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_lists', function (Blueprint $table) {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('name', 120);
            $table->string('ar_name', 150)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('uni_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('university_courses')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('intake');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('university_wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('university_courses')->cascadeOnDelete();
            $table->unique(['user_id', 'course_id']);
            $table->timestamps();
        });

        Schema::create('university_accommodation_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('ar_title')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->string('price')->nullable();
            $table->json('features')->nullable();
            $table->string('image')->nullable();
            $table->longText('details')->nullable();
            $table->longText('ar_details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_accommodation_rooms');
        Schema::dropIfExists('university_wishlists');
        Schema::dropIfExists('uni_applications');
        Schema::dropIfExists('featured_lists');
    }
};
