<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;

class SchoolSeeder extends Seeder
{
    /**
     * Seeder ini melakukan 2 hal:
     * 1. Mengisi tabel "schools" dengan data sekolah yang sudah dikumpulkan.
     * 2. Menghubungkan (update) kolom "school_id" di tabel "users"
     *    berdasarkan nama sekolah yang cocok di kolom "school" (teks lama).
     *
     * Cara pakai: tambahkan baris baru di array $daftarSekolah di bawah ini
     * setiap kali ada sekolah baru yang perlu ditambahkan.
     */
    public function run(): void
    {
        $daftarSekolah = [
            [
                'nama_sekolah' => 'Madrasah Aliyah Unggulan',
                'alamat'       => 'Rantai Baru, Pangkalan Kerinci',
                'latitude'     => 0.3832739287241727,
                'longitude'    => 101.8302219640528,
            ],
            // Tambahkan sekolah lain di sini dengan format yang sama:
            // [
            //     'nama_sekolah' => 'Nama Sekolah Lain',
            //     'alamat'       => 'Alamat sekolah',
            //     'latitude'     => 0.000000,
            //     'longitude'    => 101.000000,
            // ],
        ];

        foreach ($daftarSekolah as $data) {
            // updateOrCreate supaya seeder aman dijalankan berkali-kali
            // (tidak membuat data duplikat jika nama sekolah sudah ada)
            $school = School::updateOrCreate(
                ['nama_sekolah' => $data['nama_sekolah']],
                $data
            );

            // Cari semua user (guru) yang kolom "school" (teks lama)-nya
            // cocok dengan nama sekolah ini, lalu isi school_id-nya
            User::where('school', $data['nama_sekolah'])
                ->update(['school_id' => $school->id]);
        }
    }
}