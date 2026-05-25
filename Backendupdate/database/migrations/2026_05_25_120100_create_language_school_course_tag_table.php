<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_school_course_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_school_course_id');
            $table->foreignId('tag_id');
            $table->timestamps();

            $table->foreign('language_school_course_id', 'ls_course_tag_course_fk')
                ->references('id')
                ->on('language_school_courses')
                ->cascadeOnDelete();
            $table->foreign('tag_id', 'ls_course_tag_tag_fk')
                ->references('id')
                ->on('tags')
                ->cascadeOnDelete();
            $table->unique(
                ['language_school_course_id', 'tag_id'],
                'ls_course_tag_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_school_course_tag');
    }
};
