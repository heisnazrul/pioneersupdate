<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_school_insurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained('language_school_branches')
                ->cascadeOnDelete();
            $table->decimal('weekly_fee', 10, 2)->nullable();
            $table->decimal('admin_fee', 10, 2)->nullable();
            $table->enum('is_mandatory', ['yes', 'no'])->default('no');
            $table->timestamps();

            $table->unique('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_insurances');
    }
};
