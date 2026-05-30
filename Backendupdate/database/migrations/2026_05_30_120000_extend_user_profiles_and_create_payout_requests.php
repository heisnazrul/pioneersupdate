<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('user_profiles', 'national_id')) {
                $table->string('national_id', 64)->nullable()->after('alt_phone_e164');
            }
            if (! Schema::hasColumn('user_profiles', 'bank_account_name')) {
                $table->string('bank_account_name', 191)->nullable()->after('national_id');
            }
            if (! Schema::hasColumn('user_profiles', 'bank_name')) {
                $table->string('bank_name', 191)->nullable()->after('bank_account_name');
            }
            if (! Schema::hasColumn('user_profiles', 'bank_account_number')) {
                $table->string('bank_account_number', 64)->nullable()->after('bank_name');
            }
            if (! Schema::hasColumn('user_profiles', 'bank_iban')) {
                $table->string('bank_iban', 64)->nullable()->after('bank_account_number');
            }
            if (! Schema::hasColumn('user_profiles', 'bank_swift_code')) {
                $table->string('bank_swift_code', 32)->nullable()->after('bank_iban');
            }
        });

        if (! Schema::hasTable('payout_requests')) {
            Schema::create('payout_requests', function (Blueprint $table) {
                $table->id();
                $table->string('reference_no', 32)->unique();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
                $table->enum('referrer_type', ['student', 'agent']);
                $table->decimal('amount', 12, 2);
                $table->string('currency', 3)->default('SAR');
                $table->string('status', 20)->default('pending');
                $table->string('bank_account_name', 191)->nullable();
                $table->string('bank_name', 191)->nullable();
                $table->string('bank_account_number', 64)->nullable();
                $table->string('bank_iban', 64)->nullable();
                $table->string('bank_swift_code', 32)->nullable();
                $table->text('user_notes')->nullable();
                $table->text('admin_notes')->nullable();
                $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'created_at']);
                $table->index(['user_id', 'status']);
                $table->index(['agent_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_requests');

        Schema::table('user_profiles', function (Blueprint $table) {
            $columns = [
                'national_id',
                'bank_account_name',
                'bank_name',
                'bank_account_number',
                'bank_iban',
                'bank_swift_code',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('user_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
