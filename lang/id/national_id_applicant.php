<?php

return [
    'navigation_label' => 'Pemohon KTP',
    'plural_label' => 'Pemohon KTP',
    'model_label' => 'Pemohon KTP',

    'sections' => [
        'applicant_information' => 'Informasi Pemohon',
    ],

    'fields' => [
        'no_register' => 'Nomor Registrasi',
        'date' => 'Tanggal Registrasi',
        'national_id_number' => 'Nomor KTP',
        'name' => 'Nama Lengkap',
        'address' => 'Alamat',
        'village' => 'Desa',
        'sex' => 'Jenis Kelamin',
        'created_at' => 'Dibuat Pada',
    ],

    'placeholders' => [
        'auto_generated' => 'Dibuat otomatis',
    ],

    'sex_options' => [
        'female' => 'Perempuan',
        'male' => 'Laki-laki',
    ],

    'filters' => [
        'date_from' => 'Tanggal Dari',
        'date_until' => 'Tanggal Sampai',
    ],
];
