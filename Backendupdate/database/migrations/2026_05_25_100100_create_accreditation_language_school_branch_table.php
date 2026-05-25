<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accreditation_language_school_branch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_id');
            $table->foreignId('language_school_branch_id');
            $table->timestamps();

            $table->foreign('accreditation_id', 'acc_branch_accreditation_fk')
                ->references('id')
                ->on('accreditations')
                ->cascadeOnDelete();
            $table->foreign('language_school_branch_id', 'acc_branch_branch_fk')
                ->references('id')
                ->on('language_school_branches')
                ->cascadeOnDelete();
            $table->unique(
                ['accreditation_id', 'language_school_branch_id'],
                'accreditation_branch_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditation_language_school_branch');
    }
};
