<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 20)->nullable()->unique()->after('google_id');
            $table->string('referred_by_type', 20)->nullable()->after('referral_code');
            $table->unsignedBigInteger('referred_by_user_id')->nullable()->after('referred_by_type');
            $table->unsignedBigInteger('referred_by_agent_id')->nullable()->after('referred_by_user_id');
            $table->string('referral_code_used', 20)->nullable()->after('referred_by_agent_id');
            $table->timestamp('referred_at')->nullable()->after('referral_code_used');
            $table->decimal('referral_commission_balance', 12, 2)->default(0)->after('referred_at');
            $table->decimal('referral_commission_total', 12, 2)->default(0)->after('referral_commission_balance');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->string('referral_slug', 50)->nullable()->unique()->after('referral_code');
            $table->boolean('is_code_custom')->default(false)->after('referral_slug');
            $table->decimal('commission_balance', 12, 2)->default(0)->after('commission_percent');
            $table->decimal('total_commission_earned', 12, 2)->default(0)->after('commission_balance');
        });

        Schema::create('referral_program_settings', function (Blueprint $table) {
            $table->id();
            $table->string('scope', 20)->unique();
            $table->string('discount_type', 20)->default('percent');
            $table->decimal('discount_value', 12, 2)->default(5);
            $table->string('commission_type', 20)->default('percent');
            $table->decimal('commission_value', 12, 2)->default(3);
            $table->decimal('min_booking_amount', 12, 2)->default(0);
            $table->decimal('max_discount_amount', 12, 2)->nullable();
            $table->decimal('max_commission_amount', 12, 2)->nullable();
            $table->unsignedSmallInteger('cookie_ttl_days')->default(30);
            $table->unsignedSmallInteger('attribution_window_days')->default(365);
            $table->json('applies_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('referral_attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referred_user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('referrer_type', 20);
            $table->foreignId('referrer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('referrer_agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->string('referral_code', 20);
            $table->string('source', 20)->default('link');
            $table->string('status', 20)->default('pending');
            $table->timestamp('qualified_at')->nullable();
            $table->string('first_booking_type', 50)->nullable();
            $table->unsignedBigInteger('first_booking_id')->nullable();
            $table->timestamps();

            $table->index(['referrer_type', 'referrer_user_id']);
            $table->index(['referrer_type', 'referrer_agent_id']);
            $table->index('referral_code');
        });

        Schema::create('referral_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('referral_code', 20);
            $table->string('referrer_type', 20);
            $table->foreignId('referrer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('referrer_agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('landing_path')->nullable();
            $table->boolean('converted')->default(false);
            $table->foreignId('converted_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['referral_code', 'created_at']);
        });

        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->string('referrer_type', 20);
            $table->foreignId('referrer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('referrer_agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('referred_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('booking_type', 50);
            $table->unsignedBigInteger('booking_id');
            $table->string('booking_reference', 30);
            $table->decimal('booking_total', 12, 2);
            $table->decimal('discount_given', 12, 2)->default(0);
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->char('currency', 3)->default('SAR');
            $table->string('status', 20)->default('pending');
            $table->timestamp('payable_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['booking_type', 'booking_id']);
            $table->index(['referrer_type', 'referrer_user_id', 'status'], 'ref_comm_student_status_idx');
            $table->index(['referrer_type', 'referrer_agent_id', 'status'], 'ref_comm_agent_status_idx');
        });

        Schema::table('language_course_bookings', function (Blueprint $table) {
            $table->string('referrer_type', 20)->nullable()->after('referral_discount_amount');
            $table->foreignId('referrer_user_id')->nullable()->after('referrer_type')->constrained('users')->nullOnDelete();
            $table->foreignId('referrer_agent_id')->nullable()->after('referrer_user_id')->constrained('agents')->nullOnDelete();
            $table->decimal('referral_commission_amount', 12, 2)->default(0)->after('referrer_agent_id');
            $table->foreignId('booked_by_agent_id')->nullable()->after('referral_commission_amount')->constrained('agents')->nullOnDelete();
            $table->foreignId('booked_by_agent_user_id')->nullable()->after('booked_by_agent_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('online_course_bookings', function (Blueprint $table) {
            $table->string('referral_code', 50)->nullable()->after('selection_snapshot');
            $table->decimal('referral_discount_amount', 12, 2)->default(0)->after('referral_code');
            $table->string('referrer_type', 20)->nullable()->after('referral_discount_amount');
            $table->foreignId('referrer_user_id')->nullable()->after('referrer_type')->constrained('users')->nullOnDelete();
            $table->foreignId('referrer_agent_id')->nullable()->after('referrer_user_id')->constrained('agents')->nullOnDelete();
            $table->decimal('referral_commission_amount', 12, 2)->default(0)->after('referrer_agent_id');
            $table->foreignId('booked_by_agent_id')->nullable()->after('referral_commission_amount')->constrained('agents')->nullOnDelete();
            $table->foreignId('booked_by_agent_user_id')->nullable()->after('booked_by_agent_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('agent_students', function (Blueprint $table) {
            $table->string('source', 20)->default('manual')->after('country');
            $table->foreignId('referral_attribution_id')->nullable()->after('source')->constrained('referral_attributions')->nullOnDelete();
        });

        DB::table('referral_program_settings')->insert([
            [
                'scope' => 'student',
                'discount_type' => 'percent',
                'discount_value' => 5,
                'commission_type' => 'percent',
                'commission_value' => 3,
                'applies_to' => json_encode(['language_courses', 'online_courses']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope' => 'agent',
                'discount_type' => 'percent',
                'discount_value' => 5,
                'commission_type' => 'percent',
                'commission_value' => 5,
                'applies_to' => json_encode(['language_courses', 'online_courses']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::table('agent_students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referral_attribution_id');
            $table->dropColumn('source');
        });

        Schema::table('online_course_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('booked_by_agent_user_id');
            $table->dropConstrainedForeignId('booked_by_agent_id');
            $table->dropColumn([
                'referral_commission_amount', 'referrer_agent_id', 'referrer_user_id',
                'referrer_type', 'referral_discount_amount', 'referral_code',
            ]);
        });

        Schema::table('language_course_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('booked_by_agent_user_id');
            $table->dropConstrainedForeignId('booked_by_agent_id');
            $table->dropColumn(['referral_commission_amount', 'referrer_agent_id', 'referrer_user_id', 'referrer_type']);
        });

        Schema::dropIfExists('referral_commissions');
        Schema::dropIfExists('referral_clicks');
        Schema::dropIfExists('referral_attributions');
        Schema::dropIfExists('referral_program_settings');

        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['referral_slug', 'is_code_custom', 'commission_balance', 'total_commission_earned']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'referral_code', 'referred_by_type', 'referred_by_user_id', 'referred_by_agent_id',
                'referral_code_used', 'referred_at', 'referral_commission_balance', 'referral_commission_total',
            ]);
        });
    }
};
