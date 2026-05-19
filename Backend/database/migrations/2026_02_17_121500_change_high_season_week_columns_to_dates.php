<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('language_school_branch_high_season_fees')) {
            return;
        }

        $weekStartColumn = DB::selectOne("SHOW COLUMNS FROM `language_school_branch_high_season_fees` WHERE `Field` = 'week_start'");
        if ($weekStartColumn && str_contains(strtolower((string) $weekStartColumn->Type), 'date')) {
            return;
        }

        DB::statement("ALTER TABLE `language_school_branch_high_season_fees` ADD COLUMN `week_start_date` DATE NULL AFTER `branch_id`");
        DB::statement("ALTER TABLE `language_school_branch_high_season_fees` ADD COLUMN `week_end_date` DATE NULL AFTER `week_start_date`");

        DB::statement(
            "UPDATE `language_school_branch_high_season_fees`
             SET `week_start_date` = CASE
                 WHEN `week_start` IS NULL THEN NULL
                 ELSE DATE_ADD(MAKEDATE(YEAR(CURDATE()), 1), INTERVAL GREATEST(`week_start`, 1) - 1 WEEK)
             END"
        );

        DB::statement(
            "UPDATE `language_school_branch_high_season_fees`
             SET `week_end_date` = CASE
                 WHEN `week_end` IS NULL THEN NULL
                 ELSE DATE_ADD(MAKEDATE(YEAR(CURDATE()), 1), INTERVAL GREATEST(`week_end`, 1) - 1 WEEK)
             END"
        );

        DB::statement("ALTER TABLE `language_school_branch_high_season_fees` DROP COLUMN `week_start`, DROP COLUMN `week_end`");
        DB::statement("ALTER TABLE `language_school_branch_high_season_fees` CHANGE `week_start_date` `week_start` DATE NOT NULL, CHANGE `week_end_date` `week_end` DATE NULL");
    }

    public function down(): void
    {
        if (!Schema::hasTable('language_school_branch_high_season_fees')) {
            return;
        }

        $weekStartColumn = DB::selectOne("SHOW COLUMNS FROM `language_school_branch_high_season_fees` WHERE `Field` = 'week_start'");
        if ($weekStartColumn && str_contains(strtolower((string) $weekStartColumn->Type), 'smallint')) {
            return;
        }

        DB::statement("ALTER TABLE `language_school_branch_high_season_fees` ADD COLUMN `week_start_num` SMALLINT UNSIGNED NULL AFTER `branch_id`");
        DB::statement("ALTER TABLE `language_school_branch_high_season_fees` ADD COLUMN `week_end_num` SMALLINT UNSIGNED NULL AFTER `week_start_num`");

        DB::statement(
            "UPDATE `language_school_branch_high_season_fees`
             SET `week_start_num` = WEEK(`week_start`, 3),
                 `week_end_num` = CASE
                     WHEN `week_end` IS NULL THEN NULL
                     ELSE WEEK(`week_end`, 3)
                 END"
        );

        DB::statement("ALTER TABLE `language_school_branch_high_season_fees` DROP COLUMN `week_start`, DROP COLUMN `week_end`");
        DB::statement("ALTER TABLE `language_school_branch_high_season_fees` CHANGE `week_start_num` `week_start` SMALLINT UNSIGNED NOT NULL, CHANGE `week_end_num` `week_end` SMALLINT UNSIGNED NULL");
    }
};
