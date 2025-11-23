<?php

namespace Database\Seeders;

use App\Models\LandTitleApplicantType;
use Illuminate\Database\Seeder;

class LandTitleApplicantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Penjual', 'code' => 'seller'],
            ['name' => 'Pembeli', 'code' => 'buyer'],
            ['name' => 'Persetujuan', 'code' => 'consent'],
            ['name' => 'Saksi', 'code' => 'witness'],
            ['name' => 'PPAT', 'code' => 'land_deed_official'],
            ['name' => 'Pemohon', 'code' => 'applicant'],
        ];

        foreach ($types as $type) {
            LandTitleApplicantType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
