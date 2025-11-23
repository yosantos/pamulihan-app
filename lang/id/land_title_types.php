<?php

return [
    'navigation' => 'Tipe Surat Tanah',
    'model_label' => 'Tipe Surat Tanah',
    'plural_model_label' => 'Tipe Surat Tanah',

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
        'name' => 'Contoh: Jual Beli, Hibah, Waris',
        'code' => 'e.g. sale_purchase',
    ],

    'helpers' => [
        'code' => 'Kode unik untuk menentukan template dokumen',
    ],
];
