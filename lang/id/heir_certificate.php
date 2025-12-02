<?php

return [
    // Navigation & Model
    'navigation' => 'Ahli Waris',
    'model_label' => 'Ahli Waris',
    'plural_model_label' => 'Ahli Waris',

    // Pages
    'pages' => [
        'list' => 'Daftar Ahli Waris',
        'create' => 'Buat Ahli Waris',
        'edit' => 'Edit Ahli Waris',
        'view' => 'Lihat Ahli Waris',
    ],

    // Sections
    'sections' => [
        'certificate_information' => [
            'title' => 'Informasi Ahli Waris',
            'description' => 'Masukkan informasi dasar untuk Ahli Waris',
        ],
        'deceased_information' => [
            'title' => 'Informasi Almarhum',
            'description' => 'Masukkan informasi tentang orang yang meninggal',
        ],
        'heirs_information' => [
            'title' => 'Informasi Ahli Waris',
            'description' => 'Tambahkan semua ahli waris untuk Ahli Waris ini (minimal 1 ahli waris diperlukan)',
        ],
    ],

    // Fields
    'fields' => [
        'certificate_number' => 'Nomor Ahli Waris',
        'certificate_date' => 'Tanggal Ahli Waris',
        'year' => 'Tahun',
        'applicant_name' => 'Nama Pemohon',
        'applicant_address' => 'Alamat Pemohon',
        'phone_number' => 'Nomor Telepon',
        'deceased_name' => 'Nama Almarhum',
        'date_of_death' => 'Tanggal Meninggal',
        'place_of_death' => 'Tempat Meninggal',
        'person_in_charge' => 'Penanggung Jawab (PIC)',
        'status' => 'Status',
        'notes' => 'Catatan',
        'created_by' => 'Dibuat Oleh',
        'created_at' => 'Dibuat Pada',
        'updated_at' => 'Diperbarui Pada',
        'heirs_count' => 'Ahli Waris',
    ],

    // Field Helpers
    'helpers' => [
        'certificate_number' => 'Format: 1/2025 (otomatis dibuat)',
        'certificate_date' => 'Tanggal Ahli Waris ini diterbitkan',
        'phone_number' => 'Format telepon Indonesia (08xxx atau 62xxx). Akan diformat otomatis ke 62xxx',
        'status' => 'Status Ahli Waris saat ini',
        'person_in_charge' => 'Pilih orang yang bertanggung jawab menangani sertifikat ini',
        'date_of_death' => 'Tanggal orang tersebut meninggal',
    ],

    // Placeholders
    'placeholders' => [
        'certificate_number' => 'Otomatis dibuat saat menyimpan',
        'applicant_name' => 'Masukkan nama lengkap pemohon',
        'applicant_address' => 'Masukkan alamat lengkap pemohon',
        'phone_number' => '08xxx atau 62xxx',
        'deceased_name' => 'Masukkan nama lengkap almarhum',
        'place_of_death' => 'Masukkan tempat meninggal',
        'person_in_charge' => 'Pilih penanggung jawab sertifikat ini',
        'heir_name' => 'Masukkan nama lengkap ahli waris',
        'heir_address' => 'Masukkan alamat ahli waris (opsional)',
        'relationship' => 'contoh: Anak, Istri, Suami, dll.',
    ],

    // Heirs Section
    'heirs' => [
        'section_title' => 'Informasi Ahli Waris',
        'name' => 'Nama Ahli Waris',
        'relationship' => 'Hubungan dengan Almarhum',
        'id_number' => 'Nomor KTP',
        'address' => 'Alamat Ahli Waris',
        'add_button' => 'Tambah Ahli Waris',
        'new_heir' => 'Ahli Waris Baru',
        'relationship_helper' => 'Hubungan dengan orang yang meninggal',
    ],

    // Table Columns
    'columns' => [
        'certificate_no' => 'No. Sertifikat',
        'status' => 'Status',
        'certificate_date' => 'Tanggal Sertifikat',
        'applicant' => 'Pemohon',
        'phone_number' => 'Nomor Telepon',
        'deceased' => 'Almarhum',
        'date_of_death' => 'Tanggal Meninggal',
        'place_of_death' => 'Tempat Meninggal',
        'heirs' => 'Ahli Waris',
        'person_in_charge' => 'Penanggung Jawab',
        'created_by' => 'Dibuat Oleh',
        'created_at' => 'Dibuat Pada',
        'updated_at' => 'Diperbarui Pada',
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'year' => 'Tahun',
        'person_in_charge' => 'Penanggung Jawab',
        'created_by' => 'Dibuat Oleh',
        'certificate_date' => 'Tanggal Sertifikat',
        'date_of_death' => 'Tanggal Meninggal',
        'certificate_from' => 'Sertifikat Dari',
        'certificate_until' => 'Sertifikat Sampai',
        'death_from' => 'Meninggal Dari',
        'death_until' => 'Meninggal Sampai',
        'all_statuses' => 'Semua Status',
        'all_years' => 'Semua Tahun',
        'all_users' => 'Semua Pengguna',
    ],

    // Filter Indicators
    'filter_indicators' => [
        'certificate_from' => 'Sertifikat dari :date',
        'certificate_until' => 'Sertifikat sampai :date',
        'death_from' => 'Meninggal dari :date',
        'death_until' => 'Meninggal sampai :date',
    ],

    // Actions
    'actions' => [
        'view' => 'Lihat',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'actions' => 'Aksi',
        'send_whatsapp' => 'Kirim Pesan WhatsApp',
        'mark_completed' => 'Tandai Selesai',
        'mark_on_progress' => 'Tandai Dalam Proses',
        'export_selected' => 'Ekspor Yang Dipilih',
        'create' => 'Buat',
    ],

    // WhatsApp Form
    'whatsapp' => [
        'select_campaign' => 'Pilih Kampanye',
        'phone_number' => 'Nomor Telepon',
        'phone_number_placeholder' => '08xxxxxxxxxx atau 628xxxxxxxxxx',
        'phone_number_helper' => 'Format: 08xxxxxxxxxx atau 628xxxxxxxxxx',
        'message_preview' => 'Pratinjau Pesan',
        'no_preview' => 'Tidak ada pratinjau',
        'campaign_not_found' => 'Kampanye tidak ditemukan',
        'modal_heading' => 'Kirim Pesan WhatsApp',
        'modal_description' => 'Kirim notifikasi WhatsApp tentang sertifikat ini',
        'modal_submit' => 'Kirim Pesan',
        'phone_not_set_tooltip' => 'Nomor telepon belum diatur untuk sertifikat ini',
    ],

    // Notifications
    'notifications' => [
        'created' => 'Sertifikat berhasil dibuat.',
        'updated' => 'Sertifikat berhasil diperbarui.',
        'deleted' => 'Sertifikat berhasil dihapus.',
        'whatsapp_sent' => 'Pesan WhatsApp berhasil dikirim ke :phone',
        'whatsapp_failed' => 'Gagal mengirim pesan WhatsApp',
        'campaign_not_found' => 'Kampanye yang dipilih tidak dapat ditemukan.',
        'status_updated' => 'Status Diperbarui',
        'marked_completed' => 'Sertifikat :number ditandai selesai.',
        'marked_on_progress' => 'Sertifikat :number ditandai dalam proses.',
        'message_sent' => 'Pesan Terkirim',
        'failed_to_send' => 'Gagal Mengirim',
    ],

    // Modal Confirmations
    'modals' => [
        'mark_completed' => [
            'heading' => 'Tandai Sertifikat Sebagai Selesai',
            'description' => 'Apakah Anda yakin ingin menandai sertifikat ini sebagai selesai?',
            'submit' => 'Tandai Selesai',
        ],
        'mark_on_progress' => [
            'heading' => 'Tandai Sertifikat Sebagai Dalam Proses',
            'description' => 'Apakah Anda yakin ingin menandai sertifikat ini sebagai dalam proses?',
            'submit' => 'Tandai Dalam Proses',
        ],
    ],

    // Table Messages
    'table' => [
        'empty_state_heading' => 'Belum ada ahli waris',
        'empty_state_description' => 'Buat ahli waris pertama Anda untuk memulai.',
        'phone_copied' => 'Nomor telepon disalin!',
        'not_provided' => 'Tidak tersedia',
        'not_assigned' => 'Belum ditugaskan',
    ],

    // Export
    'export' => [
        'certificate_number' => 'Nomor Sertifikat',
        'certificate_date' => 'Tanggal Sertifikat',
        'status' => 'Status',
        'applicant_name' => 'Nama Pemohon',
        'applicant_address' => 'Alamat Pemohon',
        'phone_number' => 'Nomor Telepon',
        'deceased_name' => 'Nama Almarhum',
        'place_of_death' => 'Tempat Meninggal',
        'date_of_death' => 'Tanggal Meninggal',
        'person_in_charge' => 'Penanggung Jawab',
        'heirs_count' => 'Jumlah Ahli Waris',
        'heirs_names' => 'Nama-nama Ahli Waris',
        'created_by' => 'Dibuat Oleh',
        'created_at' => 'Dibuat Pada',
    ],

    // Global Search
    'global_search' => [
        'certificate_date' => 'Tanggal Sertifikat',
        'deceased' => 'Almarhum',
        'heirs' => ':count ahli waris',
    ],
];
