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
        Schema::create('summer_camps_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('camp_id')->constrained('language_course_summer_camps')->cascadeOnDelete();
            $table->string('whatsapp');
            $table->integer('weeks');
            $table->date('start_date');
            $table->json('special_supplements')->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summer_camps_bookings');
    }
};
