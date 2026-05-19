<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('language_school_course_material_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_school_course_id')
                ->constrained(
                    table: 'language_school_courses',
                    indexName: 'ls_cm_fee_course_id_foreign'
                )
                ->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('billing_unit', ['week', 'month', 'course']);
            $table->unsignedSmallInteger('billing_count')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_course_material_fees');
    }
};
