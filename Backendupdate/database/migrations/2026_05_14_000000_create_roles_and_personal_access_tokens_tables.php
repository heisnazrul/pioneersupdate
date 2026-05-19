<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('slug', 50)->unique();
                $table->string('name', 100);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'role_id']);
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        $now = now();
        $roles = [
            ['slug' => 'admin', 'name' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'team', 'name' => 'Team', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'counsellor', 'name' => 'Counsellor', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'agent', 'name' => 'Agent', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'school', 'name' => 'School', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'lg_student', 'name' => 'CourseEnglish Student', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'lg_agent', 'name' => 'CourseEnglish Agent', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'uni_student', 'name' => 'University Student', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'uni_agent', 'name' => 'University Agent', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('roles')->upsert($roles, ['slug'], ['name', 'updated_at']);

        $roleIds = DB::table('roles')->pluck('id', 'slug');

        DB::table('users')
            ->select(['id', 'role'])
            ->orderBy('id')
            ->chunkById(200, function ($users) use ($roleIds, $now) {
                $pivotRows = [];

                foreach ($users as $user) {
                    $roleId = $roleIds[$user->role] ?? null;

                    if ($roleId) {
                        $pivotRows[] = [
                            'user_id' => $user->id,
                            'role_id' => $roleId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                if ($pivotRows !== []) {
                    DB::table('user_roles')->insertOrIgnore($pivotRows);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
    }
};
