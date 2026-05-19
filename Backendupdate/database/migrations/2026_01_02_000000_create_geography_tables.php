<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name');
            $table->string('slug')->nullable()->unique();
            $table->string('flag')->nullable();
            $table->string('country_code', 10)->unique();
            $table->boolean('is_popular')->default(false);
            $table->string('currency_code', 10)->default('USD');
            $table->string('phone_code', 10)->nullable();
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->string('capital')->nullable();
            $table->string('continent')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ar_name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('countries');
    }
};
