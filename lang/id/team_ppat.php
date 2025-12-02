<?php

return [
    'navigation_label' => 'Tim PPAT',
    'model_label' => 'Anggota Tim',
    'plural_model_label' => 'Tim PPAT',

    'sections' => [
        'personal_information' => 'Informasi Pribadi',
        'address' => 'Alamat',
        'password' => 'Kata Sandi',
    ],

    'fields' => [
        'name' => 'Nama Lengkap',
        'email' => 'Alamat Email',
        'phone' => 'Nomor Telepon',
        'birthdate' => 'Tanggal Lahir',
        'birthplace' => 'Tempat Lahir',
        'occupation' => 'Pekerjaan',
        'national_id_number' => 'Nomor KTP',
        'road' => 'Jalan',
        'rt' => 'RT',
        'rw' => 'RW',
        'village' => 'Kelurahan/Desa',
        'district' => 'Kecamatan',
        'city' => 'Kota/Kabupaten',
        'province' => 'Provinsi',
        'password' => 'Kata Sandi',
    ],

    'placeholders' => [
        'name' => 'Masukkan nama lengkap',
        'email' => 'Masukkan alamat email',
        'phone' => 'Masukkan nomor telepon',
        'birthdate' => 'Pilih tanggal lahir',
        'birthplace' => 'Masukkan tempat lahir',
        'occupation' => 'Masukkan pekerjaan',
        'national_id_number' => 'Masukkan nomor KTP',
        'road' => 'Masukkan nama jalan',
        'rt' => 'Masukkan nomor RT',
        'rw' => 'Masukkan nomor RW',
        'village' => 'Masukkan nama kelurahan/desa',
        'district' => 'Masukkan nama kecamatan',
        'city' => 'Masukkan nama kota/kabupaten',
        'province' => 'Masukkan nama provinsi',
        'password' => 'Masukkan kata sandi',
    ],

    'helpers' => [
        'password' => 'Biarkan kosong untuk mempertahankan kata sandi saat ini',
    ],

    'columns' => [
        'name' => 'Nama',
        'email' => 'Email',
        'phone' => 'Telepon',
        'city' => 'Kota',
        'occupation' => 'Pekerjaan',
        'balance' => 'Saldo Dompet',
        'created_at' => 'Dibuat Pada',
    ],

    'filters' => [
        'has_phone' => 'Memiliki Nomor Telepon',
        'created_from' => 'Dibuat Dari',
        'created_until' => 'Dibuat Sampai',
    ],

    'actions' => [
        'deposit' => 'Setor',
        'withdrawal' => 'Tarik',
    ],

    'wallet' => [
        'current_balance' => 'Saldo Saat Ini',
        'deposit_amount' => 'Jumlah Setoran',
        'withdrawal_amount' => 'Jumlah Penarikan',
        'description' => 'Keterangan',
        'description_placeholder' => 'Masukkan keterangan transaksi (opsional)',
        'deposit_helper' => 'Masukkan jumlah yang akan disetor ke dompet',
        'withdrawal_helper' => 'Masukkan jumlah yang akan ditarik (memperbolehkan saldo negatif)',
        'manual_deposit' => 'Setoran manual',
        'manual_withdrawal' => 'Penarikan manual',
    ],

    'modals' => [
        'deposit' => [
            'heading' => 'Setor ke Dompet',
            'submit' => 'Setor',
        ],
        'withdrawal' => [
            'heading' => 'Tarik dari Dompet',
            'description' => 'Penarikan paksa memperbolehkan saldo negatif. Tarik jumlah berapapun tanpa batasan saldo.',
            'submit' => 'Tarik',
        ],
    ],

    'notifications' => [
        'deposit_success' => 'Setoran Berhasil',
        'deposit_success_body' => 'Berhasil menyetor Rp :amount ke dompet :name',
        'deposit_failed' => 'Setoran Gagal',
        'withdrawal_success' => 'Penarikan Berhasil',
        'withdrawal_success_body' => 'Berhasil menarik Rp :amount dari dompet :name',
        'withdrawal_failed' => 'Penarikan Gagal',
        'negative_balance_warning' => 'Peringatan: Saldo negatif -Rp :balance',
    ],

    'errors' => [
        'insufficient_balance' => 'Saldo tidak mencukupi. Saldo saat ini: Rp :balance',
    ],
];
