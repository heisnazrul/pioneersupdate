<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FreeshUserSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::ROLES as $role) {
            $email = $role . '@pioneers.com';
            $username = str_replace('_', '.', $role);
            $name = Str::of($role)
                ->replace('_', ' ')
                ->title()
                ->toString();

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => 'password123',
                    'role' => $role,
                    'status' => 'active',
                ]
            );

            $user->assignPrimaryRole($role);
        }

        $this->command?->info('Fresh users seeded with password: password123');

        foreach (User::ROLES as $role) {
            $this->command?->info(' - ' . $role . '@pioneers.com');
        }
    }
}
