<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Category;
use App\Models\AppCategory;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = [
            [
                'name' => 'E-KTP Online',
                'slug' => 'e-ktp-online',
                'description' => 'Layanan pembuatan dan perpanjangan KTP elektronik secara online',
                'icon_path' => '/images/icons/ktp.svg',
                'url' => '/layanan/e-ktp',
                'type' => 'internal',
                'is_active' => true,
                'order_index' => 1,
                'categories' => ['pemerintahan', 'layanan-publik'],
                'user_categories' => ['pegawai', 'pencari_kerja', 'pengusaha']
            ],
            [
                'name' => 'Puskesmas Online',
                'slug' => 'puskesmas-online',
                'description' => 'Pendaftaran antrian puskesmas dan konsultasi kesehatan online',
                'icon_path' => '/images/icons/health.svg',
                'url' => '/layanan/puskesmas',
                'type' => 'internal',
                'is_active' => true,
                'order_index' => 2,
                'categories' => ['kesehatan'],
                'user_categories' => ['pelajar', 'pegawai', 'pencari_kerja', 'pengusaha']
            ],
            [
                'name' => 'Info Sekolah',
                'slug' => 'info-sekolah',
                'description' => 'Informasi pendaftaran sekolah dan beasiswa',
                'icon_path' => '/images/icons/school.svg',
                'url' => '/layanan/sekolah',
                'type' => 'internal',
                'is_active' => true,
                'order_index' => 3,
                'categories' => ['pendidikan'],
                'user_categories' => ['pelajar', 'pegawai']
            ],
            [
                'name' => 'Lowongan Kerja',
                'slug' => 'lowongan-kerja',
                'description' => 'Portal lowongan kerja di Kabupaten Muara Enim',
                'icon_path' => '/images/icons/job.svg',
                'url' => '/layanan/lowongan',
                'type' => 'internal',
                'is_active' => true,
                'order_index' => 4,
                'categories' => ['bisnis', 'informasi'],
                'user_categories' => ['pencari_kerja', 'pelajar']
            ],
            [
                'name' => 'Perizinan Usaha',
                'slug' => 'perizinan-usaha',
                'description' => 'Layanan pembuatan izin usaha dan SIUP online',
                'icon_path' => '/images/icons/business.svg',
                'url' => '/layanan/perizinan',
                'type' => 'internal',
                'is_active' => true,
                'order_index' => 5,
                'categories' => ['bisnis', 'pemerintahan'],
                'user_categories' => ['pengusaha']
            ],
            [
                'name' => 'Info Transportasi',
                'slug' => 'info-transportasi',
                'description' => 'Jadwal transportasi umum dan informasi lalu lintas',
                'icon_path' => '/images/icons/transport.svg',
                'url' => '/layanan/transportasi',
                'type' => 'internal',
                'is_active' => true,
                'order_index' => 6,
                'categories' => ['transportasi'],
                'user_categories' => ['pelajar', 'pegawai', 'pencari_kerja', 'pengusaha']
            ],
            [
                'name' => 'Pajak Online',
                'slug' => 'pajak-online',
                'description' => 'Pembayaran pajak daerah secara online',
                'icon_path' => '/images/icons/tax.svg',
                'url' => 'https://pajak.muaraenimkab.go.id',
                'type' => 'external',
                'is_active' => true,
                'order_index' => 7,
                'categories' => ['keuangan', 'pemerintahan'],
                'user_categories' => ['pegawai', 'pengusaha']
            ],
            [
                'name' => 'Wisata Muara Enim',
                'slug' => 'wisata-muara-enim',
                'description' => 'Informasi destinasi wisata dan event di Muara Enim',
                'icon_path' => '/images/icons/tourism.svg',
                'url' => '/layanan/wisata',
                'type' => 'internal',
                'is_active' => true,
                'order_index' => 8,
                'categories' => ['wisata', 'informasi'],
                'user_categories' => ['pelajar', 'pegawai', 'pencari_kerja', 'pengusaha']
            ],
        ];

        foreach ($applications as $appData) {
            $categories = $appData['categories'];
            $userCategories = $appData['user_categories'];
            unset($appData['categories'], $appData['user_categories']);

            $application = Application::create($appData);

            // Attach categories with user categories
            foreach ($categories as $categorySlug) {
                $category = Category::where('slug', $categorySlug)->first();
                if ($category) {
                    foreach ($userCategories as $userCategory) {
                        AppCategory::create([
                            'application_id' => $application->id,
                            'category_id' => $category->id,
                            'user_category' => $userCategory,
                        ]);
                    }
                }
            }
        }
    }
}
