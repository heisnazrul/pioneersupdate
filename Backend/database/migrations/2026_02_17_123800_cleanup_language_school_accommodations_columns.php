<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('language_school_accommodations')) {
            return;
        }

        Schema::table('language_school_accommodations', function (Blueprint $table) {
            if (Schema::hasColumn('language_school_accommodations', 'school_branch_id')) {
                $table->dropConstrainedForeignId('school_branch_id');
            }
        });

        Schema::table('language_school_accommodations', function (Blueprint $table) {
            if (Schema::hasColumn('language_school_accommodations', 'slug')) {
                $table->dropUnique('language_school_accommodations_slug_unique');
            }

            $dropColumns = array_values(array_filter([
                Schema::hasColumn('language_school_accommodations', 'slug') ? 'slug' : null,
                Schema::hasColumn('language_school_accommodations', 'description') ? 'description' : null,
                Schema::hasColumn('language_school_accommodations', 'ar_description') ? 'ar_description' : null,
                Schema::hasColumn('language_school_accommodations', 'price') ? 'price' : null,
                Schema::hasColumn('language_school_accommodations', 'currency') ? 'currency' : null,
                Schema::hasColumn('language_school_accommodations', 'features') ? 'features' : null,
                Schema::hasColumn('language_school_accommodations', 'image') ? 'image' : null,
                Schema::hasColumn('language_school_accommodations', 'details') ? 'details' : null,
                Schema::hasColumn('language_school_accommodations', 'ar_details') ? 'ar_details' : null,
            ]));

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('language_school_accommodations')) {
            return;
        }

        Schema::table('language_school_accommodations', function (Blueprint $table) {
            if (!Schema::hasColumn('language_school_accommodations', 'school_branch_id')) {
                $table->foreignId('school_branch_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('language_school_branches')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('language_school_accommodations', 'slug')) {
                $table->string('slug')->nullable()->after('ar_title');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'ar_description')) {
                $table->text('ar_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'price')) {
                $table->decimal('price', 12, 2)->nullable()->after('ar_description');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('price');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'features')) {
                $table->json('features')->nullable()->after('currency');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'image')) {
                $table->string('image')->nullable()->after('features');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'details')) {
                $table->longText('details')->nullable()->after('image');
            }
            if (!Schema::hasColumn('language_school_accommodations', 'ar_details')) {
                $table->longText('ar_details')->nullable()->after('details');
            }
        });

        Schema::table('language_school_accommodations', function (Blueprint $table) {
            if (Schema::hasColumn('language_school_accommodations', 'slug')) {
                $table->unique('slug');
            }
        });
    }
};
