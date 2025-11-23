<?php

return [
    // Navigation & Model
    'navigation' => 'Pesan WhatsApp',
    'navigation_group' => 'Komunikasi',
    'model_label' => 'Pesan WhatsApp',
    'plural_model_label' => 'Pesan WhatsApp',

    // Pages
    'pages' => [
        'list' => 'Daftar Pesan WhatsApp',
        'create' => 'Kirim Pesan WhatsApp',
        'view' => 'Lihat Pesan WhatsApp',
    ],

    // Sections
    'sections' => [
        'message_details' => [
            'title' => 'Detail Pesan',
        ],
        'campaign_information' => [
            'title' => 'Informasi Kampanye',
        ],
        'campaign_variables' => [
            'title' => 'Variabel Kampanye',
        ],
        'status_information' => [
            'title' => 'Informasi Status',
        ],
        'user_tracking' => [
            'title' => 'Pelacakan Pengguna',
        ],
    ],

    // Fields
    'fields' => [
        'use_campaign' => 'Gunakan Template Kampanye',
        'campaign' => 'Pilih Kampanye',
        'phone_number' => 'Nomor Telepon',
        'message' => 'Isi Pesan',
        'status' => 'Status',
        'retry_count' => 'Jumlah Percobaan Ulang',
        'error_message' => 'Pesan Kesalahan',
        'sent_at' => 'Dikirim Pada',
        'created_by' => 'Dibuat Oleh',
        'sent_by' => 'Dikirim Oleh',
        'campaign_name' => 'Nama Kampanye',
        'variables_used' => 'Variabel yang Digunakan',
        'original_template' => 'Template Asli',
        'created_at' => 'Dibuat Pada',
    ],

    // Field Helpers
    'helpers' => [
        'use_campaign' => 'Aktifkan untuk mengirim pesan menggunakan template kampanye',
        'phone_number' => 'Format: 08xxxxxxxxxx atau 628xxxxxxxxxx',
        'character_count' => 'Karakter: :count / 1000',
        'retry_count' => 'Berapa kali pesan ini telah dicoba kirim ulang',
        'created_by_helper' => 'Pengguna yang membuat pesan ini',
        'sent_by_helper' => 'Pengguna yang mengirim atau mengirim ulang pesan ini terakhir kali',
    ],

    // Placeholders
    'placeholders' => [
        'phone_number' => '08xxxxxxxxxx atau 628xxxxxxxxxx',
        'message' => 'Masukkan pesan WhatsApp Anda di sini...',
        'select_campaign' => 'Pilih kampanye untuk melihat detail',
        'manual_message' => 'Pesan Manual',
        'not_available' => 'T/A',
        'variable_value' => 'Masukkan nilai untuk [:variable]',
    ],

    // Campaign Info
    'campaign_info' => [
        'select_to_see_details' => 'Pilih kampanye untuk melihat detail',
        'not_found' => 'Kampanye tidak ditemukan',
        'company' => 'Perusahaan',
        'description' => 'Deskripsi',
        'required_variables' => 'Variabel yang Diperlukan',
        'no_variables' => 'Tidak ada variabel yang digunakan',
    ],

    // Message Preview
    'preview' => [
        'label' => 'Pratinjau Pesan',
        'no_preview' => 'Tidak ada pratinjau',
    ],

    // Table Columns
    'columns' => [
        'phone_number' => 'Nomor Telepon',
        'message_preview' => 'Pratinjau Pesan',
        'campaign' => 'Kampanye',
        'status' => 'Status',
        'retries' => 'Percobaan Ulang',
        'created_by' => 'Dibuat Oleh',
        'sent_by' => 'Dikirim Oleh',
        'sent_at' => 'Dikirim Pada',
        'created_at' => 'Dibuat Pada',
        'manual' => 'Manual',
    ],

    // Status
    'status' => [
        'sent' => 'Terkirim',
        'failed' => 'Gagal',
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'message_type' => 'Tipe Pesan',
        'all_messages' => 'Semua pesan',
        'campaign_messages' => 'Pesan kampanye',
        'manual_messages' => 'Pesan manual',
        'sent_from' => 'Dikirim Dari',
        'sent_until' => 'Dikirim Sampai',
    ],

    // Actions
    'actions' => [
        'view' => 'Lihat',
        'resend' => 'Kirim Ulang',
        'resend_selected' => 'Kirim Ulang yang Dipilih',
    ],

    // Resend Modal
    'resend' => [
        'heading' => 'Kirim Ulang Pesan WhatsApp',
        'description' => 'Apakah Anda yakin ingin mengirim ulang pesan ini ke :phone?',
        'submit' => 'Ya, Kirim Ulang',
        'bulk_heading' => 'Kirim Ulang Pesan WhatsApp yang Gagal',
        'bulk_description' => 'Apakah Anda yakin ingin mengirim ulang semua pesan yang gagal?',
        'bulk_submit' => 'Ya, Kirim Ulang Semua',
    ],

    // Notifications
    'notifications' => [
        'created' => 'Pesan berhasil dibuat dan dikirim.',
        'resend_success' => [
            'title' => 'Pesan Berhasil Dikirim Ulang',
            'body' => 'Pesan berhasil dikirim ke :phone',
        ],
        'resend_failed' => [
            'title' => 'Gagal Mengirim Ulang Pesan',
            'body' => ':message',
        ],
        'bulk_resend_all_success' => [
            'title' => 'Semua Pesan Berhasil Dikirim Ulang',
            'body' => ':count pesan berhasil dikirim',
        ],
        'bulk_resend_partial' => [
            'title' => 'Sebagian Berhasil',
            'body' => ':success pesan berhasil dikirim, :failed gagal',
        ],
        'bulk_resend_all_failed' => [
            'title' => 'Semua Pesan Gagal',
            'body' => 'Gagal mengirim :count pesan',
        ],
    ],

    // Bulk Actions
    'bulk_actions' => [
        'delete' => 'Hapus yang Dipilih',
        'resend' => 'Kirim Ulang yang Dipilih',
    ],

    // Navigation Badge
    'badge' => [
        'sent_count' => ':count terkirim',
    ],

    // Tooltips
    'tooltips' => [
        'click_to_copy' => 'Klik untuk menyalin',
    ],
];
