<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['30000001', 'SD Negeri Contoh 1', 'SD', 'Negeri', 'Jl. Contoh No. 1', 'Banjarmasin Tengah', -3.2950000, 114.5900000],
            ['30000002', 'SD Swasta Contoh 2', 'SD', 'Swasta', 'Jl. Contoh No. 2', 'Banjarmasin Utara', -3.3100000, 114.6000000],
            ['30000003', 'SMP Negeri Contoh 1', 'SMP', 'Negeri', 'Jl. Contoh No. 3', 'Banjarmasin Selatan', -3.3200000, 114.5850000],
            ['30000004', 'SMP Swasta Contoh 2', 'SMP', 'Swasta', 'Jl. Contoh No. 4', 'Banjarmasin Timur', -3.3050000, 114.5950000],
            ['30000005', 'SMA Negeri Contoh 1', 'SMA', 'Negeri', 'Jl. Contoh No. 5', 'Banjarmasin Tengah', -3.3150000, 114.5920000],
            ['30000006', 'SMA Swasta Contoh 2', 'SMA', 'Swasta', 'Jl. Contoh No. 6', 'Banjarmasin Barat', -3.3250000, 114.6050000],
            ['30000007', 'SMK Negeri Contoh 1', 'SMK', 'Negeri', 'Jl. Contoh No. 7', 'Banjarmasin Utara', -3.3000000, 114.5800000],
            ['30000008', 'SMK Swasta Contoh 2', 'SMK', 'Swasta', 'Jl. Contoh No. 8', 'Banjarmasin Selatan', -3.3350000, 114.5900000],
        ];

        foreach ($data as [$npsn, $nama, $jenjang, $status, $alamat, $kecamatan, $lat, $lng]) {
            Sekolah::updateOrCreate(
                ['npsn' => $npsn],
                [
                    'nama' => $nama,
                    'jenjang' => $jenjang,
                    'status' => $status,
                    'alamat' => $alamat,
                    'kecamatan' => $kecamatan,
                    'telepon' => null,
                    'latitude' => $lat,
                    'longitude' => $lng,
                ]
            );
        }
    }
}