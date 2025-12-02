<?php

return [
    'navigation_label' => 'Pengaturan WhatsApp',
    'title' => 'Pengaturan Koneksi WhatsApp',

    'actions' => [
        'refresh' => 'Perbarui Status',
        'logout' => 'Keluar',
    ],

    'qr_code' => [
        'title' => 'Pindai Kode QR untuk Login',
        'description' => 'Pindai kode QR ini dengan aplikasi WhatsApp di ponsel Anda untuk terhubung',
        'auto_refresh' => 'Memperbarui otomatis setiap 10 detik...',
        'not_available' => 'Kode QR tidak tersedia. Silakan muat ulang halaman.',
    ],

    'instructions' => [
        'title' => 'Cara Menghubungkan WhatsApp',
        'step_1' => 'Buka WhatsApp di ponsel Anda',
        'step_2' => 'Ketuk Menu (⋮) atau Pengaturan dan pilih Perangkat Tertaut',
        'step_3' => 'Ketuk Tautkan Perangkat',
        'step_4' => 'Arahkan ponsel Anda ke layar ini untuk memindai kode QR',
    ],

    'status' => [
        'connected' => 'Terhubung',
        'connected_description' => 'Akun WhatsApp Anda berhasil terhubung dan siap mengirim pesan.',
        'unknown' => 'Tidak dapat menentukan status koneksi WhatsApp. Silakan coba muat ulang halaman.',
    ],

    'modals' => [
        'logout' => [
            'heading' => 'Keluar dari WhatsApp',
            'description' => 'Apakah Anda yakin ingin memutuskan koneksi akun WhatsApp ini? Anda perlu memindai kode QR lagi untuk terhubung kembali.',
            'submit' => 'Keluar',
        ],
    ],

    'notifications' => [
        'status_check_failed' => 'Gagal memeriksa status WhatsApp',
        'connection_error' => 'Kesalahan Koneksi',
        'logout_success' => 'Berhasil keluar dari WhatsApp',
        'logout_failed' => 'Gagal keluar',
        'logout_error' => 'Kesalahan Logout',
    ],
];
