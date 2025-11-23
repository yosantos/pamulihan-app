<?php

namespace Database\Seeders;

use App\Models\LandTitleType;
use Illuminate\Database\Seeder;

class LandTitleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Jual Beli', 'code' => 'sale_purchase'],
            ['name' => 'Hibah', 'code' => 'grant'],
            ['name' => 'Waris', 'code' => 'inheritance'],
            ['name' => 'Tukar Menukar', 'code' => 'exchange'],
        ];

        foreach ($types as $type) {
            LandTitleType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
