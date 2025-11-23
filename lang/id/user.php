<?php

return [
    // Navigation & Model
    'navigation' => 'Pengguna',
    'navigation_group' => 'Manajemen Pengguna',
    'model_label' => 'Pengguna',
    'plural_model_label' => 'Pengguna',

    // Pages
    'pages' => [
        'list' => 'Daftar Pengguna',
        'create' => 'Buat Pengguna',
        'edit' => 'Edit Pengguna',
        'view' => 'Lihat Pengguna',
    ],

    // Sections
    'sections' => [
        'user_information' => [
            'title' => 'Informasi Pengguna',
        ],
        'personal_information' => [
            'title' => 'Informasi Pribadi',
            'description' => 'Informasi pribadi pengguna termasuk NIK, tempat tanggal lahir, dan pekerjaan',
        ],
        'address_information' => [
            'title' => 'Informasi Alamat',
            'description' => 'Alamat lengkap pengguna termasuk provinsi, kota, kecamatan, kelurahan, jalan, RT dan RW',
        ],
        'avatar' => [
            'title' => 'Avatar',
        ],
        'documents' => [
            'title' => 'Dokumen',
        ],
        'roles_permissions' => [
            'title' => 'Peran & Izin',
        ],
    ],

    // Fields
    'fields' => [
        'name' => 'Nama',
        'email' => 'Email',
        'phone' => 'Nomor Telepon',
        'password' => 'Kata Sandi',
        'national_id_number' => 'Nomor Induk Kependudukan (NIK)',
        'birthplace' => 'Tempat Lahir',
        'birthdate' => 'Tanggal Lahir',
        'occupation' => 'Pekerjaan',
        'province' => 'Provinsi',
        'city' => 'Kota/Kabupaten',
        'district' => 'Kecamatan',
        'village' => 'Kelurahan/Desa',
        'road' => 'Jalan',
        'rt' => 'RT',
        'rw' => 'RW',
        'avatar' => 'Avatar',
        'documents' => 'Dokumen',
        'roles' => 'Peran',
        'balance' => 'Saldo Dompet',
        'created_at' => 'Dibuat Pada',
    ],

    // Field Helpers
    'helpers' => [
        'email' => 'Opsional. Jika kosong, akan dibuat otomatis dari nama dengan akhiran @mypamulihan.com',
        'password' => 'Opsional. Jika kosong, akan dibuat kata sandi acak yang aman',
        'phone' => 'Format: 628xxxxxxxxxx atau 08xxxxxxxxxx',
        'national_id_number' => 'NIK terdiri dari 16 digit angka sesuai KTP',
        'rt' => 'Nomor RT (Rukun Tetangga)',
        'rw' => 'Nomor RW (Rukun Warga)',
        'avatar' => 'Unggah avatar pengguna (maks 2MB)',
        'documents' => 'Unggah dokumen pengguna (maks 5MB per file, maksimal 10 file)',
    ],

    // Placeholders
    'placeholders' => [
        'phone_not_available' => 'T/A',
        'national_id_number' => '1234567890123456',
        'birthplace' => 'Jakarta',
        'birthdate' => 'Pilih tanggal lahir',
        'occupation' => 'Wiraswasta',
        'province' => 'DKI Jakarta',
        'city' => 'Jakarta Selatan',
        'district' => 'Kebayoran Baru',
        'village' => 'Gunung',
        'road' => 'Jl. Raya Kebayoran No. 123',
        'rt' => '001',
        'rw' => '002',
    ],

    // Table Columns
    'columns' => [
        'avatar' => 'Avatar',
        'name' => 'Nama',
        'email' => 'Email',
        'phone' => 'Nomor Telepon',
        'national_id_number' => 'NIK',
        'birthdate' => 'Tanggal Lahir',
        'balance' => 'Saldo Dompet',
        'roles' => 'Peran',
        'created_at' => 'Dibuat Pada',
    ],

    // Filters
    'filters' => [
        'roles' => 'Peran',
    ],

    // Actions
    'actions' => [
        'view' => 'Lihat',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'add_balance' => 'Tambah Saldo',
        'deduct_balance' => 'Kurangi Saldo',
        'transfer_balance' => 'Transfer Saldo',
        'send_whatsapp' => 'Kirim WhatsApp',
    ],

    // Wallet Actions
    'wallet' => [
        'add_balance' => [
            'label' => 'Tambah Saldo',
            'amount' => 'Jumlah untuk Ditambahkan',
            'description' => 'Deskripsi',
            'modal_heading' => 'Tambah Saldo',
        ],
        'deduct_balance' => [
            'label' => 'Kurangi Saldo',
            'amount' => 'Jumlah untuk Dikurangi',
            'description' => 'Deskripsi',
            'modal_heading' => 'Kurangi Saldo',
        ],
        'transfer_balance' => [
            'label' => 'Transfer Saldo',
            'recipient' => 'Transfer Ke',
            'amount' => 'Jumlah untuk Ditransfer',
            'description' => 'Deskripsi',
            'modal_heading' => 'Transfer Saldo',
        ],
    ],

    // WhatsApp Action
    'whatsapp' => [
        'label' => 'Kirim WhatsApp',
        'phone_number' => 'Nomor Telepon',
        'phone_helper' => 'Format: 628xxxxxxxxxx',
        'message' => 'Pesan',
        'message_placeholder' => 'Masukkan pesan Anda di sini...',
        'modal_heading' => 'Kirim WhatsApp',
    ],

    // Notifications
    'notifications' => [
        'balance_added' => [
            'title' => 'Saldo Ditambahkan',
            'body' => 'IDR :amount telah ditambahkan ke dompet :name.',
        ],
        'balance_deducted' => [
            'title' => 'Saldo Dikurangi',
            'body' => 'IDR :amount telah dikurangi dari dompet :name.',
        ],
        'insufficient_balance' => [
            'title' => 'Saldo Tidak Cukup',
            'body' => 'Pengguna tidak memiliki saldo yang cukup. Saldo saat ini: IDR :balance',
        ],
        'transfer_successful' => [
            'title' => 'Transfer Berhasil',
            'body' => 'IDR :amount telah ditransfer dari :from ke :to.',
        ],
        'whatsapp_sent' => [
            'title' => 'WhatsApp Terkirim',
            'body' => 'Pesan telah dikirim ke :name.',
        ],
        'whatsapp_error' => [
            'title' => 'Kesalahan WhatsApp',
            'body' => ':message',
        ],
        'invalid_phone' => [
            'title' => 'Nomor Telepon Tidak Valid',
            'body' => 'Harap berikan nomor telepon Indonesia yang valid.',
        ],
    ],

    // Relation Managers
    'relations' => [
        'transactions' => 'Transaksi',
    ],

    // Bulk Actions
    'bulk_actions' => [
        'delete' => 'Hapus yang Dipilih',
    ],
];
