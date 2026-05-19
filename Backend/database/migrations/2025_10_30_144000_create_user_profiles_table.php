<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            // Note: countries/cities tables don't exist yet in Backend, so we make these nullable integers for now
            // or we need to migrate countries tables first. 
            // The plan said "move all... one by one". 
            // For now, let's keep the foreign keys but be aware we might need to create those tables or comment out constraints.
            // Since we haven't migrated countries yet, I will comment out the constraints but keep columns.

            $table->foreignId('nationality_country_id')->nullable();
            // ->constrained('countries')->nullOnDelete();
            $table->foreignId('current_country_id')->nullable();
            // ->constrained('countries')->nullOnDelete();
            $table->foreignId('current_city_id')->nullable();
            // ->constrained('cities')->nullOnDelete();

            $table->string('address_line', 191)->nullable();
            $table->string('postal_code', 20)->nullable();

            $table->string('secondary_email', 191)->nullable();
            $table->string('alt_phone_e164', 32)->nullable();

            $table->timestamps();

            $table->index(['current_country_id', 'current_city_id']);
            $table->index('nationality_country_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
