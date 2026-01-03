<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'role' => 'guest',
                'phone' => '081234567890',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '081234567891',
            ]
        );

        User::updateOrCreate(
            ['email' => 'receptionist@example.com'],
            [
                'name' => 'Receptionist User',
                'email' => 'receptionist@example.com',
                'password' => bcrypt('password'),
                'role' => 'receptionist',
                'phone' => '081234567892',
            ]
        );

        // seed villas, room types and sample bookings
        $this->call(\Database\Seeders\VillaSeeder::class);
    }
}
