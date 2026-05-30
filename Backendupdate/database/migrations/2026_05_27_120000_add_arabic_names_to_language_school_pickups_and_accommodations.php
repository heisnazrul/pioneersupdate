<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('language_school_pickups', function (Blueprint $table) {
            $table->string('pickup_location_ar')->nullable()->after('pickup_location');
        });

        Schema::table('language_school_accommodations', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('language_school_pickups', function (Blueprint $table) {
            $table->dropColumn('pickup_location_ar');
        });

        Schema::table('language_school_accommodations', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });
    }
};
