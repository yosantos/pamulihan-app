<?php

return [
    // Navigation & Model
    'navigation' => 'Kartu Keluarga',
    'model_label' => 'Kartu Keluarga',
    'plural_model_label' => 'Kartu Keluarga',

    // Pages
    'pages' => [
        'list' => 'Daftar Kartu Keluarga',
        'create' => 'Buat Kartu Keluarga',
        'edit' => 'Edit Kartu Keluarga',
        'view' => 'Lihat Kartu Keluarga',
    ],

    // Sections
    'sections' => [
        'registration_information' => [
            'title' => 'Informasi Pendaftaran',
            'description' => 'Masukkan detail pendaftaran untuk permohonan kartu keluarga',
        ],
        'applicant_information' => [
            'title' => 'Informasi Pemohon',
            'description' => 'Masukkan informasi pribadi pemohon',
        ],
        'admin_section' => [
            'title' => 'Catatan Administratif',
            'description' => 'Catatan internal dan informasi administratif',
        ],
        'whatsapp_preview' => [
            'title' => 'Pratinjau Notifikasi WhatsApp',
            'description' => 'Pratinjau pesan WhatsApp yang akan dikirim ke pemohon saat pendaftaran',
            'label' => 'Pratinjau Pesan',
            'no_campaign' => 'Tidak ada campaign yang dikonfigurasi. Silakan konfigurasi campaign pendaftaran di Pengaturan Kartu Keluarga.',
            'campaign_not_found' => 'Campaign yang dikonfigurasi tidak ditemukan.',
        ],
    ],

    // Fields
    'fields' => [
        'no_registration' => 'Nomor Registrasi',
        'name' => 'Nama Pemohon',
        'date' => 'Tanggal Pendaftaran',
        'due_date' => 'Tanggal Jatuh Tempo',
        'national_id_number' => 'Nomor Induk Kependudukan (NIK)',
        'address' => 'Alamat',
        'village' => 'Desa',
        'phone_number' => 'Nomor Telepon',
        'note' => 'Catatan',
        'status' => 'Status',
        'admin_memo' => 'Memo Admin',
        'rejection_reason' => 'Alasan Penolakan',
        'person_in_charge' => 'Penanggung Jawab',
        'created_at' => 'Dibuat Pada',
        'updated_at' => 'Diperbarui Pada',
    ],

    // Field Helpers
    'helpers' => [
        'no_registration' => 'Format: 001/2025 (dibuat otomatis)',
        'date' => 'Tanggal saat pemohon datang mendaftar',
        'due_date' => 'Dihitung otomatis sebagai tanggal pendaftaran + 7 hari',
        'national_id_number' => 'Nomor kartu identitas nasional pemohon (NIK)',
        'phone_number' => 'Format telepon Indonesia (08xxx atau 62xxx). Akan diformat otomatis ke 62xxx',
        'status' => 'Status permohonan saat ini',
        'person_in_charge' => 'Pilih orang yang bertanggung jawab menangani permohonan ini',
        'admin_memo' => 'Catatan administratif internal (tidak terlihat oleh pemohon)',
    ],

    // Placeholders
    'placeholders' => [
        'no_registration' => 'Dibuat otomatis saat menyimpan',
        'name' => 'Masukkan nama lengkap pemohon',
        'national_id_number' => 'Masukkan NIK 16 digit',
        'address' => 'Masukkan alamat lengkap',
        'village' => 'Pilih desa',
        'phone_number' => '08xxx atau 62xxx',
        'note' => 'Catatan tambahan atau permintaan khusus',
        'admin_memo' => 'Catatan administratif internal',
        'rejection_reason' => 'Alasan penolakan',
        'person_in_charge' => 'Pilih penanggung jawab permohonan ini',
    ],

    // Table Columns
    'columns' => [
        'no_registration' => 'No. Registrasi',
        'status' => 'Status',
        'name' => 'Nama Pemohon',
        'date' => 'Tanggal Daftar',
        'due_date' => 'Jatuh Tempo',
        'national_id_number' => 'NIK',
        'phone_number' => 'No. Telepon',
        'village' => 'Desa',
        'person_in_charge' => 'Penanggung Jawab',
        'created_at' => 'Dibuat Pada',
        'updated_at' => 'Diperbarui Pada',
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'person_in_charge' => 'Penanggung Jawab',
        'village' => 'Desa',
        'date_from' => 'Pendaftaran Dari',
        'date_until' => 'Pendaftaran Sampai',
        'overdue' => 'Permohonan Terlambat',
        'all_statuses' => 'Semua Status',
        'all_users' => 'Semua Pengguna',
        'all_villages' => 'Semua Desa',
    ],

    // Filter Indicators
    'filter_indicators' => [
        'date_from' => 'Pendaftaran dari :date',
        'date_until' => 'Pendaftaran sampai :date',
    ],

    // Actions
    'actions' => [
        'view' => 'Lihat',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'actions' => 'Aksi',
        'reject' => 'Tolak Permohonan',
        'mark_completed' => 'Tandai Selesai',
        'mark_on_progress' => 'Tandai Dalam Proses',
        'create' => 'Buat',
        'send_whatsapp' => 'Kirim WhatsApp',
        'download_file' => 'Unduh Dokumen Selesai',
    ],

    // Create WhatsApp Notification
    'create' => [
        'notification_section' => 'Notifikasi WhatsApp (Opsional)',
        'notification_description' => 'Kirim notifikasi WhatsApp kepada pemohon setelah pendaftaran',
        'applicant_info' => 'Informasi Pemohon',
        'applicant_details' => 'Nama: :name | Telepon: :phone',
        'select_campaign' => 'Pilih Campaign WhatsApp',
        'campaign_placeholder' => 'Pilih template campaign (opsional)',
        'campaign_helper' => 'Pilih campaign WhatsApp untuk otomatis memberitahu pemohon setelah pendaftaran. Kosongkan untuk melewati notifikasi.',
        'message_preview' => 'Pratinjau Pesan',
        'no_preview' => 'Pilih campaign untuk melihat pratinjau pesan',
        'campaign_not_found' => 'Campaign tidak ditemukan',
        'send_notification' => 'Kirim Notifikasi WhatsApp',
        'modal_heading' => 'Kirim Notifikasi Pendaftaran',
        'modal_description' => 'Kirim notifikasi WhatsApp kepada pemohon tentang pendaftaran kartu keluarga mereka',
        'modal_submit' => 'Kirim Notifikasi',
        'modal_cancel' => 'Lewati',
    ],

    // Reject Application
    'reject' => [
        'applicant_info' => 'Informasi Pemohon',
        'applicant_details' => 'Nama: :name | Telepon: :phone',
        'reason' => 'Alasan Penolakan',
        'reason_placeholder' => 'Masukkan alasan penolakan permohonan ini',
        'select_campaign' => 'Pilih Campaign Penolakan',
        'campaign_placeholder' => 'Pilih template notifikasi penolakan',
        'message_preview' => 'Pratinjau Pesan',
        'no_preview' => 'Pilih campaign untuk melihat pratinjau pesan',
        'no_campaign_configured' => 'Tidak ada campaign penolakan yang dikonfigurasi. Silakan konfigurasi di Pengaturan Kartu Keluarga.',
        'campaign_not_found' => 'Campaign tidak ditemukan',
        'modal_heading' => 'Tolak Permohonan',
        'modal_description' => 'Berikan alasan penolakan dan beritahu pemohon melalui WhatsApp',
        'modal_submit' => 'Tolak & Kirim Notifikasi',
    ],

    // Complete Application
    'complete' => [
        'applicant_info' => 'Informasi Pemohon',
        'applicant_details' => 'Nama: :name | Telepon: :phone',
        'select_campaign' => 'Pilih Campaign Penyelesaian',
        'campaign_placeholder' => 'Pilih template notifikasi penyelesaian',
        'file_upload' => 'Unggah Dokumen Selesai (PDF) - Opsional',
        'file_upload_helper' => 'Opsional: Unggah dokumen Kartu Keluarga yang sudah selesai (hanya PDF, maksimal 10MB). Jika diunggah, file akan dikirim ke pemohon melalui WhatsApp bersama dengan pesan.',
        'message_preview' => 'Pratinjau Pesan',
        'no_preview' => 'Pilih campaign untuk melihat pratinjau pesan',
        'no_campaign_configured' => 'Tidak ada campaign penyelesaian yang dikonfigurasi. Silakan konfigurasi di Pengaturan Kartu Keluarga.',
        'campaign_not_found' => 'Campaign tidak ditemukan',
        'modal_heading' => 'Tandai Selesai',
        'modal_description' => 'Tandai permohonan ini sebagai selesai dan beritahu pemohon melalui WhatsApp dengan dokumen selesai',
        'modal_submit' => 'Selesai & Kirim Notifikasi',
    ],

    // WhatsApp Resend
    'whatsapp' => [
        'applicant_info' => 'Informasi Pemohon',
        'applicant_details' => 'Nama: :name | Telepon: :phone | Registrasi: :registration',
        'select_campaign' => 'Pilih Campaign WhatsApp',
        'campaign_placeholder' => 'Pilih template campaign',
        'message_preview' => 'Pratinjau Pesan',
        'no_preview' => 'Pilih campaign untuk melihat pratinjau pesan',
        'campaign_not_found' => 'Campaign tidak ditemukan',
        'modal_heading' => 'Kirim Pesan WhatsApp',
        'modal_description' => 'Kirim atau kirim ulang notifikasi WhatsApp kepada pemohon',
        'modal_submit' => 'Kirim Pesan',
        'phone_not_set' => 'Nomor telepon tidak tersedia',
    ],

    // Notifications
    'notifications' => [
        'created' => 'Pendaftaran kartu keluarga berhasil dibuat.',
        'updated' => 'Pendaftaran kartu keluarga berhasil diperbarui.',
        'deleted' => 'Pendaftaran kartu keluarga berhasil dihapus.',
        'notification_sent' => 'Notifikasi Terkirim',
        'notification_failed' => 'Notifikasi Gagal',
        'whatsapp_sent' => 'Notifikasi WhatsApp berhasil dikirim untuk registrasi :registration',
        'whatsapp_failed' => 'Gagal mengirim notifikasi WhatsApp',
        'whatsapp_failed_detail' => 'Gagal mengirim notifikasi: :error',
        'status_updated' => 'Status Diperbarui',
        'marked_completed' => 'Permohonan ditandai selesai.',
        'marked_rejected' => 'Permohonan ditandai ditolak.',
        'marked_on_progress' => 'Permohonan :registration ditandai dalam proses.',
        'created_and_notified' => 'Pendaftaran Dibuat & Notifikasi Terkirim',
        'created_notification_failed' => 'Pendaftaran Dibuat',
        'rejected_and_notified' => 'Permohonan Ditolak & Notifikasi Terkirim',
        'rejection_sent' => 'Notifikasi penolakan terkirim untuk registrasi :registration',
        'rejected_notification_failed' => 'Permohonan Ditolak',
        'rejection_status_updated' => 'Status permohonan diperbarui menjadi ditolak, tetapi notifikasi tidak terkirim.',
        'completed_and_notified' => 'Permohonan Selesai & Notifikasi Terkirim',
        'completion_sent' => 'Notifikasi penyelesaian terkirim untuk registrasi :registration',
        'completion_sent_with_file' => 'Notifikasi penyelesaian dengan dokumen terkirim untuk registrasi :registration',
        'completed_notification_failed' => 'Permohonan Selesai',
        'completion_status_updated' => 'Status permohonan diperbarui menjadi selesai, tetapi notifikasi tidak terkirim.',
        'message_sent' => 'Pesan Berhasil Dikirim',
        'failed_to_send' => 'Gagal Mengirim Pesan',
        'campaign_not_found' => 'Campaign tidak ditemukan',
        'file_not_found' => 'File Tidak Ditemukan',
    ],

    // Modal Confirmations
    'modals' => [
        'mark_on_progress' => [
            'heading' => 'Tandai Dalam Proses',
            'description' => 'Apakah Anda yakin ingin menandai permohonan ini sebagai dalam proses?',
            'submit' => 'Tandai Dalam Proses',
        ],
    ],

    // Table Messages
    'table' => [
        'empty_state_heading' => 'Belum ada pendaftaran kartu keluarga',
        'empty_state_description' => 'Buat pendaftaran kartu keluarga pertama Anda untuk memulai.',
        'phone_copied' => 'Nomor telepon disalin!',
        'not_assigned' => 'Belum ditugaskan',
        'overdue' => 'Terlambat',
    ],

    // Global Search
    'global_search' => [
        'registration_date' => 'Tanggal Pendaftaran',
        'due_date' => 'Tanggal Jatuh Tempo',
        'status' => 'Status',
    ],
];
