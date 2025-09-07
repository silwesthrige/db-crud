<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some pending users for testing
        \App\Models\User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'status' => 'pending',
            'phone' => '+1-555-0123',
            'bio' => 'Software Developer with 5 years of experience in web development.',
        ]);

        \App\Models\User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'status' => 'pending',
            'phone' => '+1-555-0124',
            'bio' => 'Project Manager specializing in event coordination.',
        ]);

        \App\Models\User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike.johnson@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'status' => 'approved',
            'approved_at' => now()->subDays(5),
            'approved_by' => 1, // Admin user
            'phone' => '+1-555-0125',
            'bio' => 'Marketing Specialist with event planning experience.',
        ]);

        \App\Models\User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah.wilson@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'status' => 'rejected',
            'approved_by' => 1, // Admin user
            'rejection_reason' => 'Insufficient information provided during registration.',
            'phone' => '+1-555-0126',
            'bio' => 'New graduate looking for opportunities.',
        ]);

        echo "Sample users created:\n";
        echo "- John Doe (pending)\n";
        echo "- Jane Smith (pending)\n";
        echo "- Mike Johnson (approved)\n";
        echo "- Sarah Wilson (rejected)\n";
    }
}
