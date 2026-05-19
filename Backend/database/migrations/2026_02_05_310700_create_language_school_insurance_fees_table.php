<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_school_insurance_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('language_school_branches')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('billing_unit', ['week', 'month', 'course'])->default('week');
            $table->unsignedSmallInteger('billing_count')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_insurance_fees');
    }
};
