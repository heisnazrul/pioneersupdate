<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable(); // Consolidated from secondary migration
            $table->string('ar_name');
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            // Legacy did not have soft deletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
