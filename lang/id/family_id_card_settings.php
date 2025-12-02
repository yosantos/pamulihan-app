<?php

return [
    'title' => 'Pengaturan Kartu Keluarga',
    'heading' => 'Pengaturan Notifikasi WhatsApp Kartu Keluarga',

    'navigation' => [
        'label' => 'Pengaturan Kartu Keluarga',
    ],

    'section' => [
        'title' => 'Konfigurasi Campaign WhatsApp',
        'description' => 'Konfigurasi campaign WhatsApp untuk notifikasi otomatis pada berbagai tahap pendaftaran Kartu Keluarga.',
    ],

    'fields' => [
        'registration_campaign' => 'Campaign Pendaftaran',
        'rejection_campaign' => 'Campaign Penolakan',
        'completion_campaign' => 'Campaign Selesai',
    ],

    'placeholders' => [
        'select_campaign' => 'Pilih campaign',
    ],

    'helpers' => [
        'registration_campaign' => 'Campaign ini akan dikirim otomatis saat pendaftaran Kartu Keluarga baru dibuat.',
        'rejection_campaign' => 'Campaign ini akan dikirim otomatis saat pendaftaran ditolak.',
        'completion_campaign' => 'Campaign ini akan dikirim otomatis saat pendaftaran ditandai selesai.',
    ],

    'actions' => [
        'save' => 'Simpan Pengaturan',
    ],

    'notifications' => [
        'saved' => 'Pengaturan Disimpan',
        'settings_updated' => 'Pengaturan notifikasi Kartu Keluarga berhasil diperbarui.',
    ],
];
