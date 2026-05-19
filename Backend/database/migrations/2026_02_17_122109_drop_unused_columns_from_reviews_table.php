<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'facebook_link',
                'twitter_link',
                'instagram_link',
                'linkedin_link',
                'screenshots',
                'video',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('ar_review_text');
            $table->string('facebook_link')->nullable()->after('rating');
            $table->string('twitter_link')->nullable()->after('facebook_link');
            $table->string('instagram_link')->nullable()->after('twitter_link');
            $table->string('linkedin_link')->nullable()->after('instagram_link');
            $table->json('screenshots')->nullable()->after('linkedin_link');
            $table->string('video')->nullable()->after('screenshots');
        });
    }
};
