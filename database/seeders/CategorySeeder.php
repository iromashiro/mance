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
            Category::create($category);
        }
    }
}
