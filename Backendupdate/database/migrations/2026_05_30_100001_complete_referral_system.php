<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('referral_commissions')) {
            Schema::table('referral_commissions', function (Blueprint $table) {
                if (! $this->indexExists('referral_commissions', 'ref_comm_student_status_idx')) {
                    $table->index(['referrer_type', 'referrer_user_id', 'status'], 'ref_comm_student_status_idx');
                }
                if (! $this->indexExists('referral_commissions', 'ref_comm_agent_status_idx')) {
                    $table->index(['referrer_type', 'referrer_agent_id', 'status'], 'ref_comm_agent_status_idx');
                }
            });
        }

        if (! Schema::hasColumn('language_course_bookings', 'referrer_type')) {
            Schema::table('language_course_bookings', function (Blueprint $table) {
                $table->string('referrer_type', 20)->nullable()->after('referral_discount_amount');
                $table->foreignId('referrer_user_id')->nullable()->after('referrer_type')->constrained('users')->nullOnDelete();
                $table->foreignId('referrer_agent_id')->nullable()->after('referrer_user_id')->constrained('agents')->nullOnDelete();
                $table->decimal('referral_commission_amount', 12, 2)->default(0)->after('referrer_agent_id');
                $table->foreignId('booked_by_agent_id')->nullable()->after('referral_commission_amount')->constrained('agents')->nullOnDelete();
                $table->foreignId('booked_by_agent_user_id')->nullable()->after('booked_by_agent_id')->constrained('users')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('online_course_bookings', 'referral_code')) {
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
        }

        if (! Schema::hasColumn('agent_students', 'source')) {
            Schema::table('agent_students', function (Blueprint $table) {
                $table->string('source', 20)->default('manual')->after('country');
                $table->foreignId('referral_attribution_id')->nullable()->after('source')->constrained('referral_attributions')->nullOnDelete();
            });
        }

        if (Schema::hasTable('referral_program_settings') && DB::table('referral_program_settings')->count() === 0) {
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

        if (! DB::table('migrations')->where('migration', '2026_05_30_100000_create_referral_system_tables')->exists()) {
            DB::table('migrations')->insert([
                'migration' => '2026_05_30_100000_create_referral_system_tables',
                'batch' => (int) DB::table('migrations')->max('batch') + 1,
            ]);
        }
    }

    public function down(): void
    {
        // Completion migration — no rollback needed.
    }

    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $result = $connection->select(
                "SELECT COUNT(*) AS aggregate FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?",
                [$table, $index],
            );

            return (int) ($result[0]->aggregate ?? 0) > 0;
        }

        $database = $connection->getDatabaseName();
        $result = $connection->select(
            'SELECT COUNT(*) AS aggregate FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $index],
        );

        return (int) ($result[0]->aggregate ?? 0) > 0;
    }
};
