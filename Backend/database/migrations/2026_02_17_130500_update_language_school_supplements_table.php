<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('language_school_supplements')) {
            return;
        }

        Schema::table('language_school_supplements', function (Blueprint $table) {
            $dropColumns = array_values(array_filter([
                Schema::hasColumn('language_school_supplements', 'currency') ? 'currency' : null,
                Schema::hasColumn('language_school_supplements', 'billing_unit') ? 'billing_unit' : null,
                Schema::hasColumn('language_school_supplements', 'billing_count') ? 'billing_count' : null,
            ]));

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }

            if (!Schema::hasColumn('language_school_supplements', 'start_date')) {
                $table->date('start_date')->nullable()->after('amount');
            }

            if (!Schema::hasColumn('language_school_supplements', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('language_school_supplements')) {
            return;
        }

        Schema::table('language_school_supplements', function (Blueprint $table) {
            if (!Schema::hasColumn('language_school_supplements', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('amount');
            }

            if (!Schema::hasColumn('language_school_supplements', 'billing_unit')) {
                $table->string('billing_unit', 50)->nullable()->after('currency');
            }

            if (!Schema::hasColumn('language_school_supplements', 'billing_count')) {
                $table->unsignedSmallInteger('billing_count')->default(1)->after('billing_unit');
            }

            $dropColumns = array_values(array_filter([
                Schema::hasColumn('language_school_supplements', 'start_date') ? 'start_date' : null,
                Schema::hasColumn('language_school_supplements', 'end_date') ? 'end_date' : null,
            ]));

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
