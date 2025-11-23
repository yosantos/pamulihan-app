<?php

return [
    'navigation' => 'Tipe Pemohon',
    'model_label' => 'Tipe Pemohon',
    'plural_model_label' => 'Tipe Pemohon',

    'fields' => [
        'name' => 'Nama',
        'code' => 'Kode',
    ],

    'columns' => [
        'name' => 'Nama',
        'code' => 'Kode',
        'usage_count' => 'Digunakan',
        'created_at' => 'Dibuat Pada',
    ],

    'placeholders' => [
        'name' => 'Contoh: Penjual, Pembeli, Saksi',
        'code' => 'e.g. seller',
    ],

    'helpers' => [
        'code' => 'Kode unik untuk menentukan peran dalam dokumen',
    ],
];
