<?php

namespace Database\Seeders;

use App\Models\CertificationScheme;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LspSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@lsp.id'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Skema Sertifikasi
        $s1 = CertificationScheme::firstOrCreate(
            ['scheme_code' => 'JWD-2026'],
            [
                'scheme_name' => 'Junior Web Developer',
                'description' => 'Standar okupasi nasional bidang pemrograman terstruktur dan antarmuka web responsif.'
            ]
        );

        $s2 = CertificationScheme::firstOrCreate(
            ['scheme_code' => 'DMA-2026'],
            [
                'scheme_name' => 'Digital Marketing Analyst',
                'description' => 'Skema keahlian analitik data promosi dan strategi digital marketing.'
            ]
        );

        // 3. Participants
        Participant::firstOrCreate(
            ['registration_number' => 'REG-2026-001'],
            [
                'scheme_id' => $s1->id,
                'full_name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone_number' => '081234567890',
                'address' => 'Jl. Merdeka No. 10, Jakarta Pusat',
                'status' => 'Kompensasi / Kompeten',
            ]
        );

        Participant::firstOrCreate(
            ['registration_number' => 'REG-2026-002'],
            [
                'scheme_id' => $s1->id,
                'full_name' => 'Siti Rahmawati',
                'email' => 'siti.rahma@example.com',
                'phone_number' => '082198765432',
                'address' => 'Jl. Diponegoro No. 22, Bandung',
                'status' => 'Belum Kompeten',
            ]
        );
    }
}
