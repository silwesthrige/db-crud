<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@eventmanager.com'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@eventmanager.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'status' => 'approved',
                'approved_at' => now(),
                'phone' => '+1234567890',
                'bio' => 'System Administrator with full access to manage users and events.',
                'email_verified_at' => now(),
            ]
        );

        echo "Default admin account created:\n";
        echo "Email: admin@eventmanager.com\n";
        echo "Password: admin123\n";
    }
}
