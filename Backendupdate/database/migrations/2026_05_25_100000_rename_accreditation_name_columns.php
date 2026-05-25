<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accreditations', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->renameColumn('name_ar', 'ar_name');
        });
    }

    public function down(): void
    {
        Schema::table('accreditations', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->renameColumn('ar_name', 'name_ar');
        });
    }
};
