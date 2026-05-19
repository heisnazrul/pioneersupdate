<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'team',
            'counsellor',
            'uni_agent',
            'agent',
            'lg_agent',
            'school',
            'lg_student',
            'uni_student',
        ];

        foreach ($roles as $role) {
            User::updateOrCreate(
                ['email' => "{$role}@test.com"],
                [
                    'name' => ucfirst(str_replace('_', ' ', $role)) . ' User',
                    'role' => $role,
                    'username' => "{$role}_user",
                    'password' => Hash::make('1234'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'remember_token' => Str::random(10),
                ]
            );
        }
    }
}
