<?php

return [
    // Navigation & Model
    'navigation' => 'Kampanye WhatsApp',
    'navigation_group' => 'Komunikasi',
    'model_label' => 'Kampanye WhatsApp',
    'plural_model_label' => 'Kampanye WhatsApp',

    // Pages
    'pages' => [
        'list' => 'Daftar Kampanye WhatsApp',
        'create' => 'Buat Kampanye WhatsApp',
        'edit' => 'Edit Kampanye WhatsApp',
        'view' => 'Lihat Kampanye WhatsApp',
    ],

    // Sections
    'sections' => [
        'campaign_information' => [
            'title' => 'Informasi Kampanye',
        ],
        'message_template' => [
            'title' => 'Template Pesan',
        ],
        'dynamic_variables' => [
            'title' => 'Variabel Dinamis',
            'description' => 'Tentukan variabel dinamis yang perlu disediakan saat mengirim pesan menggunakan kampanye ini',
        ],
    ],

    // Fields
    'fields' => [
        'name' => 'Nama Kampanye',
        'company_name' => 'Nama Perusahaan',
        'description' => 'Deskripsi',
        'is_active' => 'Aktif',
        'template' => 'Template',
        'variables' => 'Variabel yang Diperlukan',
        'usage_count' => 'Penggunaan',
        'creator' => 'Dibuat Oleh',
        'created_at' => 'Dibuat Pada',
    ],

    // Field Helpers
    'helpers' => [
        'name' => 'Nama deskriptif untuk kampanye ini',
        'company_name' => 'Ini akan menggantikan [Name_Company] dalam template',
        'is_active' => 'Kampanye yang tidak aktif tidak dapat digunakan untuk mengirim pesan',
        'template' => 'Gunakan [nama_variabel] untuk placeholder. [Name_Company] akan diganti secara otomatis dengan nama perusahaan di atas.',
        'variables' => 'Ini adalah variabel dinamis yang harus disediakan saat mengirim pesan. Name_Company disertakan secara otomatis.',
    ],

    // Placeholders
    'placeholders' => [
        'name' => 'contoh: Kampanye OTP',
        'company_name' => 'contoh: Aplikasi Pamulihan',
        'description' => 'Deskripsi singkat tentang tujuan kampanye ini',
        'template' => '[Name_Company]. Halo, OTP Anda adalah [code]. Silakan gunakan dalam waktu 5 menit.',
        'variables' => 'Tambahkan nama variabel (contoh: code, name, amount)',
    ],

    // Template Info
    'template' => [
        'character_count' => ':count karakter',
        'detected_variables' => 'Variabel yang Terdeteksi',
        'no_variables' => 'Tidak ada variabel yang terdeteksi',
        'static_label' => 'statis',
        'dynamic_label' => 'dinamis',
    ],

    // Table Columns
    'columns' => [
        'name' => 'Nama Kampanye',
        'company' => 'Perusahaan',
        'template_preview' => 'Pratinjau Template',
        'variables' => 'Variabel',
        'active' => 'Aktif',
        'usage' => 'Penggunaan',
        'created_by' => 'Dibuat Oleh',
        'created_at' => 'Dibuat Pada',
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'all_campaigns' => 'Semua kampanye',
        'active_campaigns' => 'Kampanye aktif',
        'inactive_campaigns' => 'Kampanye tidak aktif',
        'has_usage' => 'Pernah digunakan',
    ],

    // Actions
    'actions' => [
        'view' => 'Lihat',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'preview' => 'Pratinjau',
        'duplicate' => 'Duplikat',
        'send_message' => 'Kirim Pesan',
        'activate' => 'Aktifkan',
        'deactivate' => 'Nonaktifkan',
    ],

    // Preview Modal
    'preview' => [
        'heading' => 'Pratinjau Kampanye',
        'close' => 'Tutup',
    ],

    // Modal Confirmations
    'modals' => [
        'delete' => [
            'heading' => 'Hapus Kampanye',
            'description' => 'Apakah Anda yakin ingin menghapus kampanye ini? Pesan yang dikirim menggunakan kampanye ini akan tetap ada tetapi akan kehilangan referensi kampanye.',
        ],
        'duplicate' => [
            'heading' => 'Duplikat Kampanye',
            'description' => 'Apakah Anda yakin ingin menduplikat kampanye ini?',
        ],
        'activate' => [
            'heading' => 'Aktifkan Kampanye',
            'description' => 'Apakah Anda yakin ingin mengaktifkan kampanye yang dipilih?',
        ],
        'deactivate' => [
            'heading' => 'Nonaktifkan Kampanye',
            'description' => 'Apakah Anda yakin ingin menonaktifkan kampanye yang dipilih?',
        ],
    ],

    // Notifications
    'notifications' => [
        'created' => 'Kampanye berhasil dibuat.',
        'updated' => 'Kampanye berhasil diperbarui.',
        'deleted' => 'Kampanye berhasil dihapus.',
        'duplicated' => [
            'title' => 'Kampanye diduplikat',
            'body' => 'Kampanye \':name\' berhasil diduplikat.',
        ],
        'activated' => [
            'title' => 'Kampanye diaktifkan',
            'body' => ':count kampanye telah diaktifkan.',
        ],
        'deactivated' => [
            'title' => 'Kampanye dinonaktifkan',
            'body' => ':count kampanye telah dinonaktifkan.',
        ],
    ],

    // Bulk Actions
    'bulk_actions' => [
        'delete' => 'Hapus yang Dipilih',
        'activate' => 'Aktifkan',
        'deactivate' => 'Nonaktifkan',
    ],
];
