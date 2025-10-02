<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Infrastruktur',
                'slug' => 'infrastruktur',
                'icon' => '🛤️',
                'description' => 'Laporan masalah jalan, jembatan, dan infrastruktur lainnya',
                'is_active' => true
            ],
            [
                'name' => 'Kebersihan',
                'slug' => 'kebersihan',
                'icon' => '🗑️',
                'description' => 'Laporan masalah sampah, kebersihan lingkungan',
                'is_active' => true
            ],
            [
                'name' => 'Keamanan',
                'slug' => 'keamanan',
                'icon' => '🚨',
                'description' => 'Laporan gangguan keamanan dan ketertiban',
                'is_active' => true
            ],
            [
                'name' => 'Penerangan',
                'slug' => 'penerangan',
                'icon' => '💡',
                'description' => 'Laporan lampu jalan mati atau rusak',
                'is_active' => true
            ],
            [
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
                'icon' => '🏥',
                'description' => 'Laporan masalah layanan kesehatan',
                'is_active' => true
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'icon' => '🎓',
                'description' => 'Laporan masalah fasilitas pendidikan',
                'is_active' => true
            ],
            [
                'name' => 'Transportasi',
                'slug' => 'transportasi',
                'icon' => '🚌',
                'description' => 'Laporan masalah transportasi umum',
                'is_active' => true
            ],
            [
                'name' => 'Air & Sanitasi',
                'slug' => 'air-sanitasi',
                'icon' => '💧',
                'description' => 'Laporan masalah air bersih dan sanitasi',
                'is_active' => true
            ],
            [
                'name' => 'Taman & RTH',
                'slug' => 'taman-rth',
                'icon' => '🌳',
                'description' => 'Laporan masalah taman dan ruang terbuka hijau',
                'is_active' => true
            ],
            [
                'name' => 'Lainnya',
                'slug' => 'lainnya',
                'icon' => '📋',
                'description' => 'Laporan masalah lainnya',
                'is_active' => true
            ],
        ];

        foreach ($categories as $category) {
            ComplaintCategory::create($category);
        }
    }
}
