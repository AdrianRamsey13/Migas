<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'data' => [
                    'name'              => 'Admin Migas',
                    'email'             => 'admin@migas.com',
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
                'role' => 'admin',
            ],
            [
                'data' => [
                    'name'              => 'Supervisor Lapangan',
                    'email'             => 'supervisor@migas.com',
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
                'role' => 'supervisor',
            ],
            [
                'data' => [
                    'name'              => 'Teknisi Lapangan',
                    'email'             => 'technician@migas.com',
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
                'role' => 'technician',
            ],
        ];

        foreach ($users as $entry) {
            $user = User::firstOrCreate(['email' => $entry['data']['email']], $entry['data']);
            $user->syncRoles([$entry['role']]);
        }
    }
}
