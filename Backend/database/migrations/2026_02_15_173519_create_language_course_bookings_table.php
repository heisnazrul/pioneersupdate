<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('language_course_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('language_school_courses')->cascadeOnDelete();

            // Nullable foreign keys for optional selections
            $table->foreignId('accommodation_id')->nullable()->constrained('language_school_accommodations')->nullOnDelete();
            $table->foreignId('pickup_id')->nullable()->constrained('language_school_pickups')->nullOnDelete();
            $table->foreignId('insurance_id')->nullable()->constrained('language_school_insurance_fees')->nullOnDelete();

            $table->string('whatsapp');
            $table->integer('user_age')->nullable();
            $table->integer('weeks');
            $table->date('start_date');

            $table->integer('accommodation_weeks')->nullable();
            // $table->date('accommodation_start_date')->nullable(); // Usually same as start_date or calculated

            $table->json('supplements_ids')->nullable(); // Store array of IDs

            $table->decimal('final_price', 10, 2);
            $table->string('currency', 3)->default('GBP');

            $table->string('status')->default('pending'); // pending, confirmed, cancelled
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_course_bookings');
    }
};
