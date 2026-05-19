<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            if (Schema::hasColumn('universities', 'rank')) {
                $table->dropColumn('rank');
            }
            $table->integer('qs_ranking')->nullable()->after('website');
            $table->integer('the_ranking')->nullable()->after('qs_ranking');
            $table->integer('shanghai_ranking')->nullable()->after('the_ranking');
        });
    }

    public function down(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            if (Schema::hasColumn('universities', 'qs_ranking')) {
                $table->dropColumn('qs_ranking');
            }
            if (Schema::hasColumn('universities', 'the_ranking')) {
                $table->dropColumn('the_ranking');
            }
            if (Schema::hasColumn('universities', 'shanghai_ranking')) {
                $table->dropColumn('shanghai_ranking');
            }
            $table->integer('rank')->nullable()->after('website');
        });
    }
};
