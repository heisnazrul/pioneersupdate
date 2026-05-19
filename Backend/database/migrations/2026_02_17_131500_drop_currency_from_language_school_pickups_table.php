<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('language_school_pickups')) {
            return;
        }

        Schema::table('language_school_pickups', function (Blueprint $table) {
            if (Schema::hasColumn('language_school_pickups', 'currency')) {
                $table->dropColumn('currency');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('language_school_pickups')) {
            return;
        }

        Schema::table('language_school_pickups', function (Blueprint $table) {
            if (!Schema::hasColumn('language_school_pickups', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('price');
            }
        });
    }
};
