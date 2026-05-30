<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['language_course_bookings', 'online_course_bookings'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->timestamp('paid_at')->nullable()->after('notes');
                $table->string('payment_method')->nullable()->after('paid_at');
                $table->string('payment_reference')->nullable()->after('payment_method');
                $table->string('payment_proof_path')->nullable()->after('payment_reference');
                $table->text('payment_notes')->nullable()->after('payment_proof_path');
                $table->foreignId('payment_verified_by')->nullable()->after('payment_notes')->constrained('users')->nullOnDelete();
                $table->timestamp('payment_verified_at')->nullable()->after('payment_verified_by');
            });
        }

        Schema::create('booking_payment_events', function (Blueprint $table) {
            $table->id();
            $table->string('booking_type');
            $table->unsignedBigInteger('booking_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('note')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['booking_type', 'booking_id']);
        });

        Schema::create('staff_student_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source')->default('manual');
            $table->timestamps();

            $table->unique(['staff_user_id', 'student_user_id']);
        });

        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('status')->default('draft');
            $table->string('booking_type');
            $table->foreignId('student_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('language_school_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->json('selection_snapshot')->nullable();
            $table->json('pricing_snapshot')->nullable();
            $table->json('currency_snapshot')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('display_currency', 8)->default('SAR');
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->string('converted_booking_type')->nullable();
            $table->unsignedBigInteger('converted_booking_id')->nullable();
            $table->timestamps();

            $table->index(['status', 'assigned_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('staff_student_assignments');
        Schema::dropIfExists('booking_payment_events');

        foreach (['language_course_bookings', 'online_course_bookings'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('payment_verified_by');
                $table->dropColumn([
                    'paid_at',
                    'payment_method',
                    'payment_reference',
                    'payment_proof_path',
                    'payment_notes',
                    'payment_verified_at',
                ]);
            });
        }
    }
};
