<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mance.go.id',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'category' => null,
            'email_verified_at' => now(),
        ]);

        // Create sample users for each category
        $categories = ['pelajar', 'pegawai', 'pencari_kerja', 'pengusaha'];

        foreach ($categories as $index => $category) {
            User::create([
                'name' => 'User ' . ucfirst($category),
                'email' => $category . '@example.com',
                'password' => Hash::make('password123'),
                'role' => 'masyarakat',
                'category' => $category,
                'email_verified_at' => now(),
            ]);
        }

        // Create additional sample users
        $sampleUsers = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'category' => 'pegawai'],
            ['name' => 'Siti Rahayu', 'email' => 'siti@example.com', 'category' => 'pelajar'],
            ['name' => 'Ahmad Wijaya', 'email' => 'ahmad@example.com', 'category' => 'pengusaha'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'category' => 'pencari_kerja'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@example.com', 'category' => 'pegawai'],
        ];

        foreach ($sampleUsers as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'role' => 'masyarakat',
                'category' => $userData['category'],
                'email_verified_at' => now(),
            ]);
        }
    }
}
