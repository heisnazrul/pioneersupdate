<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('language_school_insurance_fees')) {
            return;
        }

        Schema::table('language_school_insurance_fees', function (Blueprint $table) {
            if (!Schema::hasColumn('language_school_insurance_fees', 'name')) {
                $table->string('name')->nullable()->after('branch_id');
            }

            if (!Schema::hasColumn('language_school_insurance_fees', 'ar_name')) {
                $table->string('ar_name')->nullable()->after('name');
            }

            if (!Schema::hasColumn('language_school_insurance_fees', 'admin_charge')) {
                $table->decimal('admin_charge', 12, 2)->nullable()->after('amount');
            }

            if (!Schema::hasColumn('language_school_insurance_fees', 'valid_from')) {
                $table->date('valid_from')->nullable()->after('billing_count');
            }

            if (!Schema::hasColumn('language_school_insurance_fees', 'valid_to')) {
                $table->date('valid_to')->nullable()->after('valid_from');
            }

            if (Schema::hasColumn('language_school_insurance_fees', 'currency')) {
                $table->dropColumn('currency');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('language_school_insurance_fees')) {
            return;
        }

        Schema::table('language_school_insurance_fees', function (Blueprint $table) {
            if (!Schema::hasColumn('language_school_insurance_fees', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('amount');
            }

            $dropColumns = array_values(array_filter([
                Schema::hasColumn('language_school_insurance_fees', 'name') ? 'name' : null,
                Schema::hasColumn('language_school_insurance_fees', 'ar_name') ? 'ar_name' : null,
                Schema::hasColumn('language_school_insurance_fees', 'admin_charge') ? 'admin_charge' : null,
                Schema::hasColumn('language_school_insurance_fees', 'valid_from') ? 'valid_from' : null,
                Schema::hasColumn('language_school_insurance_fees', 'valid_to') ? 'valid_to' : null,
            ]));

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
