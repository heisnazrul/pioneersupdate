<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_course_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('language_school_id')->constrained('language_schools')->cascadeOnDelete();
            $table->foreignId('online_course_id')->constrained('language_online_courses')->cascadeOnDelete();

            $table->string('status', 20)->default('pending');
            $table->string('source', 20)->default('coursesat');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 50);
            $table->string('contact_whatsapp', 50)->nullable();

            $table->unsignedSmallInteger('weeks')->nullable();
            $table->date('start_date');

            $table->decimal('course_fee', 12, 2)->default(0);
            $table->decimal('registration_fee', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('total_amount', 12, 2);

            $table->char('base_currency', 3)->default('GBP');
            $table->char('display_currency', 3);
            $table->decimal('exchange_rate', 12, 6)->nullable();
            $table->decimal('conversion_fee_percent', 5, 2)->default(0);
            $table->decimal('conversion_fee_amount', 12, 2)->default(0);
            $table->json('currency_snapshot')->nullable();
            $table->json('pricing_snapshot');
            $table->json('selection_snapshot')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'created_at']);
            $table->index(['online_course_id', 'status']);
            $table->index(['language_school_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_course_bookings');
    }
};
