<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // User Categories (MUST MATCH users.category enum)
            ['name' => 'Pelajar', 'slug' => 'pelajar'],
            ['name' => 'Pegawai', 'slug' => 'pegawai'],
            ['name' => 'Pencari Kerja', 'slug' => 'pencari_kerja'],
            ['name' => 'Pengusaha', 'slug' => 'pengusaha'],

            // General Categories (untuk apps yang umum)
            ['name' => 'Kesehatan', 'slug' => 'kesehatan'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan'],
            ['name' => 'Transportasi', 'slug' => 'transportasi'],
            ['name' => 'Bisnis', 'slug' => 'bisnis'],
            ['name' => 'Pemerintahan', 'slug' => 'pemerintahan'],
            ['name' => 'Layanan Publik', 'slug' => 'layanan-publik'],
            ['name' => 'Informasi', 'slug' => 'informasi'],
            ['name' => 'Keuangan', 'slug' => 'keuangan'],
            ['name' => 'Sosial', 'slug' => 'sosial'],
            ['name' => 'Wisata', 'slug' => 'wisata'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
