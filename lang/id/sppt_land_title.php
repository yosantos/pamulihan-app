<?php

return [
    'navigation_label' => 'SPPT',
    'model_label' => 'SPPT',
    'plural_model_label' => 'SPPT',

    'sections' => [
        'basic_information' => 'Informasi Dasar',
        'area_information' => 'Informasi Luas',
    ],

    'fields' => [
        'number' => 'Nomor SPPT',
        'year' => 'Tahun',
        'owner' => 'Pemilik',
        'block' => 'Blok',
        'village' => 'Desa',
        'land_area' => 'Luas Tanah',
        'building_area' => 'Luas Bangunan',
        'references_count' => 'Referensi',
        'created_at' => 'Dibuat Pada',
    ],

    'placeholders' => [
        'number' => 'Masukkan nomor SPPT',
        'year' => 'Masukkan tahun SPPT',
        'owner' => 'Masukkan nama pemilik',
        'block' => 'Masukkan blok',
        'village' => 'Pilih desa',
        'land_area' => 'Masukkan luas tanah',
        'building_area' => 'Masukkan luas bangunan',
    ],

    'helpers' => [
        'number' => 'Nomor unik SPPT pajak bumi dan bangunan',
    ],

    'filters' => [
        'year' => 'Tahun',
        'village' => 'Desa',
    ],
];
