<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_school_pickups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained('language_school_branches')
                ->cascadeOnDelete();
            $table->string('pickup_location');
            $table->decimal('fee', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['branch_id', 'pickup_location']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_pickups');
    }
};
