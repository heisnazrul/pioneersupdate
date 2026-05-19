<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_course_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_code')->unique();
            $table->string('name');
            $table->string('ar_name');
            $table->text('description')->nullable();
            $table->text('ar_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_course_types');
    }
};
