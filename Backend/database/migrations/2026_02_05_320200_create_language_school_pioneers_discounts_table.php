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
        Schema::create('language_school_pioneers_discounts', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('ar_name')->nullable();
            $table->unsignedInteger('weeks');
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->string('discount_full_for')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_school_pioneers_discounts');
    }
};
