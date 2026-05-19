<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('language_school_accommodations', function (Blueprint $table) {
            if (!Schema::hasColumn('language_school_accommodations', 'school_branch_id')) {
                $table->foreignId('school_branch_id')->nullable()->after('id')
                    ->constrained('language_school_branches')->nullOnDelete();
            }
            if (!Schema::hasColumn('language_school_accommodations', 'language_course_tag_id')) {
                $table->foreignId('language_course_tag_id')->nullable()->after('school_branch_id')
                    ->constrained('language_course_tags')->nullOnDelete();
            }
            if (!Schema::hasColumn('language_school_accommodations', 'bedroom_type_id')) {
                $table->foreignId('bedroom_type_id')->nullable()->after('language_course_tag_id')
                    ->constrained('bedroom_types')->nullOnDelete();
            }
            if (!Schema::hasColumn('language_school_accommodations', 'bathroom_type_id')) {
                $table->foreignId('bathroom_type_id')->nullable()->after('bedroom_type_id')
                    ->constrained('bathroom_types')->nullOnDelete();
            }
            if (!Schema::hasColumn('language_school_accommodations', 'meal_plan_id')) {
                $table->foreignId('meal_plan_id')->nullable()->after('bathroom_type_id')
                    ->constrained('meal_plans')->nullOnDelete();
            }
            if (!Schema::hasColumn('language_school_accommodations', 'required_age')) {
                $table->unsignedInteger('required_age')->nullable()->after('meal_plan_id');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'fee_per_week')) {
                $table->decimal('fee_per_week', 12, 2)->nullable()->after('required_age');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'admin_charge')) {
                $table->decimal('admin_charge', 12, 2)->nullable()->after('fee_per_week');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'under18_supplement_per_week')) {
                $table->decimal('under18_supplement_per_week', 12, 2)->nullable()->after('admin_charge');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'notes')) {
                $table->text('notes')->nullable()->after('under18_supplement_per_week');
            }
        });

        if (Schema::hasColumn('language_school_accommodations', 'branch_id')) {
            DB::table('language_school_accommodations')
                ->whereNull('school_branch_id')
                ->update(['school_branch_id' => DB::raw('branch_id')]);
        }
    }

    public function down(): void
    {
        Schema::table('language_school_accommodations', function (Blueprint $table) {
            if (Schema::hasColumn('language_school_accommodations', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('language_school_accommodations', 'under18_supplement_per_week')) {
                $table->dropColumn('under18_supplement_per_week');
            }
            if (Schema::hasColumn('language_school_accommodations', 'admin_charge')) {
                $table->dropColumn('admin_charge');
            }
            if (Schema::hasColumn('language_school_accommodations', 'fee_per_week')) {
                $table->dropColumn('fee_per_week');
            }
            if (Schema::hasColumn('language_school_accommodations', 'required_age')) {
                $table->dropColumn('required_age');
            }
            if (Schema::hasColumn('language_school_accommodations', 'meal_plan_id')) {
                $table->dropConstrainedForeignId('meal_plan_id');
            }
            if (Schema::hasColumn('language_school_accommodations', 'bathroom_type_id')) {
                $table->dropConstrainedForeignId('bathroom_type_id');
            }
            if (Schema::hasColumn('language_school_accommodations', 'bedroom_type_id')) {
                $table->dropConstrainedForeignId('bedroom_type_id');
            }
            if (Schema::hasColumn('language_school_accommodations', 'language_course_tag_id')) {
                $table->dropConstrainedForeignId('language_course_tag_id');
            }
            if (Schema::hasColumn('language_school_accommodations', 'school_branch_id')) {
                $table->dropConstrainedForeignId('school_branch_id');
            }
        });
    }
};
