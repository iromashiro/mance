<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $newsData = [
            [
                'title' => 'Pembukaan Pendaftaran Beasiswa Muara Enim Cerdas 2025',
                'slug' => 'pembukaan-pendaftaran-beasiswa-muara-enim-cerdas-2025',
                'content' => 'Pemerintah Kabupaten Muara Enim membuka pendaftaran program Beasiswa Muara Enim Cerdas untuk tahun 2025. Program ini ditujukan bagi pelajar dan mahasiswa berprestasi dari keluarga kurang mampu. Pendaftaran dibuka mulai tanggal 15 Januari hingga 15 Februari 2025. Kuota beasiswa tersedia untuk 500 penerima dengan berbagai jenjang pendidikan.',
                'excerpt' => 'Pemerintah Kabupaten Muara Enim membuka pendaftaran program Beasiswa Muara Enim Cerdas untuk tahun 2025.',
                'thumbnail_path' => '/images/news/beasiswa-2025.jpg',
                'category' => 'Pendidikan',
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Perbaikan Jalan Provinsi di Kecamatan Lawang Kidul Dimulai',
                'slug' => 'perbaikan-jalan-provinsi-kecamatan-lawang-kidul-dimulai',
                'content' => 'Dinas Pekerjaan Umum Provinsi Sumatera Selatan memulai proyek perbaikan jalan provinsi sepanjang 15 km di Kecamatan Lawang Kidul. Proyek senilai Rp 45 miliar ini diharapkan selesai dalam 6 bulan. Selama masa perbaikan, pengendara diminta untuk berhati-hati dan mengikuti rambu pengalihan arus lalu lintas.',
                'excerpt' => 'Dinas PU Provinsi Sumsel memulai proyek perbaikan jalan provinsi sepanjang 15 km di Kecamatan Lawang Kidul.',
                'thumbnail_path' => '/images/news/perbaikan-jalan.jpg',
                'category' => 'Infrastruktur',
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Festival Sriwijaya 2025 Akan Digelar di Muara Enim',
                'slug' => 'festival-sriwijaya-2025-digelar-muara-enim',
                'content' => 'Festival Sriwijaya 2025 akan digelar di Kabupaten Muara Enim pada tanggal 20-25 Maret 2025. Festival budaya tahunan ini akan menampilkan berbagai pertunjukan seni tradisional, pameran kerajinan, dan kuliner khas Sumatera Selatan. Pengunjung dapat menikmati pertunjukan tari tradisional, musik daerah, dan berbagai kompetisi budaya.',
                'excerpt' => 'Festival Sriwijaya 2025 akan digelar di Kabupaten Muara Enim pada tanggal 20-25 Maret 2025.',
                'thumbnail_path' => '/images/news/festival-sriwijaya.jpg',
                'category' => 'Event',
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Layanan Puskesmas Online Resmi Diluncurkan',
                'slug' => 'layanan-puskesmas-online-resmi-diluncurkan',
                'content' => 'Dinas Kesehatan Kabupaten Muara Enim resmi meluncurkan layanan Puskesmas Online yang terintegrasi dengan aplikasi MANCE. Melalui layanan ini, masyarakat dapat mendaftar antrian, konsultasi online, dan mengakses riwayat kesehatan secara digital. Layanan ini sudah tersedia di 15 puskesmas di seluruh kabupaten.',
                'excerpt' => 'Dinas Kesehatan resmi meluncurkan layanan Puskesmas Online yang terintegrasi dengan aplikasi MANCE.',
                'thumbnail_path' => '/images/news/puskesmas-online.jpg',
                'category' => 'Kesehatan',
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Program Padat Karya 2025 Buka 5000 Lowongan',
                'slug' => 'program-padat-karya-2025-buka-5000-lowongan',
                'content' => 'Pemerintah Kabupaten Muara Enim membuka 5000 lowongan untuk Program Padat Karya Tahun 2025. Program ini bertujuan untuk mengurangi angka pengangguran dan meningkatkan infrastruktur desa. Pendaftaran dilakukan melalui aplikasi MANCE atau kantor desa setempat. Prioritas diberikan kepada warga yang terdampak ekonomi.',
                'excerpt' => 'Pemkab Muara Enim membuka 5000 lowongan untuk Program Padat Karya Tahun 2025.',
                'thumbnail_path' => '/images/news/padat-karya.jpg',
                'category' => 'Ekonomi',
                'author_id' => $admin->id,
                'is_published' => true,
                'published_at' => now()->subHours(5),
            ],
            [
                'title' => 'Pembangunan Taman Kota Modern di Pusat Kota',
                'slug' => 'pembangunan-taman-kota-modern-pusat-kota',
                'content' => 'Pembangunan taman kota modern seluas 2 hektar di pusat Kota Muara Enim akan segera dimulai. Taman ini akan dilengkapi dengan area bermain anak, jogging track, dan WiFi gratis. Proyek senilai Rp 8 miliar ini diharapkan menjadi ruang publik yang nyaman bagi warga.',
                'excerpt' => 'Pembangunan taman kota modern seluas 2 hektar di pusat Kota Muara Enim akan segera dimulai.',
                'thumbnail_path' => '/images/news/taman-kota.jpg',
                'category' => 'Pembangunan',
                'author_id' => $admin->id,
                'is_published' => false,
                'published_at' => null,
            ],
        ];

        foreach ($newsData as $news) {
            News::create($news);
        }
    }
}
