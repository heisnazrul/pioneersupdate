<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_course_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('language_school_id')->constrained('language_schools')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('language_school_courses')->cascadeOnDelete();

            $table->string('status', 20)->default('pending');
            $table->string('source', 20)->default('coursesat');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 50);
            $table->string('contact_whatsapp', 50)->nullable();

            $table->unsignedSmallInteger('weeks');
            $table->date('start_date');
            $table->unsignedTinyInteger('user_age')->nullable();
            $table->foreignId('accommodation_id')->nullable()->constrained('language_school_accommodations')->nullOnDelete();
            $table->foreignId('pickup_id')->nullable()->constrained('language_school_pickups')->nullOnDelete();
            $table->unsignedSmallInteger('accommodation_weeks')->nullable();

            $table->json('insurance_ids')->nullable();
            $table->json('supplement_ids')->nullable();
            $table->json('selection_snapshot')->nullable();

            $table->decimal('course_weekly_fee', 12, 2)->default(0);
            $table->decimal('course_total', 12, 2)->default(0);

            $table->decimal('accommodation_weekly_fee', 12, 2)->default(0);
            $table->decimal('accommodation_total', 12, 2)->default(0);
            $table->boolean('accommodation_waived')->default(false);
            $table->decimal('accommodation_original', 12, 2)->nullable();

            $table->decimal('registration_fee', 12, 2)->default(0);
            $table->decimal('material_fee', 12, 2)->default(0);
            $table->decimal('mandatory_fee', 12, 2)->default(0);
            $table->decimal('pickup_fee', 12, 2)->default(0);
            $table->boolean('pickup_waived')->default(false);
            $table->decimal('pickup_original', 12, 2)->nullable();
            $table->decimal('insurance_total', 12, 2)->default(0);
            $table->decimal('insurance_admin_fee', 12, 2)->default(0);
            $table->decimal('acc_supplements_total', 12, 2)->default(0);
            $table->decimal('other_supplements_total', 12, 2)->default(0);

            $table->decimal('course_discount_percent', 5, 2)->default(0);
            $table->decimal('course_discount_amount', 12, 2)->default(0);
            $table->decimal('pioneers_discount_total', 12, 2)->default(0);

            $table->string('coupon_code', 50)->nullable();
            $table->decimal('coupon_discount_amount', 12, 2)->default(0);
            $table->string('referral_code', 50)->nullable();
            $table->decimal('referral_discount_amount', 12, 2)->default(0);

            $table->decimal('subtotal', 12, 2);
            $table->decimal('total_amount', 12, 2);

            $table->char('base_currency', 3)->default('GBP');
            $table->char('display_currency', 3);
            $table->decimal('exchange_rate', 12, 6)->nullable();
            $table->decimal('conversion_fee_percent', 5, 2)->default(0);
            $table->decimal('conversion_fee_amount', 12, 2)->default(0);
            $table->json('currency_snapshot')->nullable();
            $table->json('pricing_snapshot');

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'created_at']);
            $table->index(['course_id', 'status']);
            $table->index(['language_school_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_course_bookings');
    }
};
