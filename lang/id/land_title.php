<?php

return [
    'navigation_label' => 'Surat Tanah',
    'model_label' => 'Surat Tanah',
    'plural_model_label' => 'Surat Tanah',

    'sections' => [
        'land_title_information' => 'Informasi Surat Tanah',
        'transaction_details' => 'Detail Transaksi',
        'fees_and_taxes' => 'Biaya & Pajak',
        'border_information' => 'Batas-Batas Tanah',
        'applicants' => 'Pemohon / Pihak Terkait',
    ],

    'fields' => [
        'land_title_number' => 'Nomor Surat Tanah',
        'land_title_type' => 'Jenis Surat Tanah',
        'village' => 'Kelurahan/Desa',
        'seller' => 'Penjual',
        'buyer' => 'Pembeli',
        'is_heir' => 'Adalah Ahli Waris',
        'heir_from_name' => 'Ahli Waris Dari (Nama Almarhum)',
        'death_place' => 'Tempat Meninggal',
        'death_date' => 'Tanggal Meninggal',
        'death_certificate_number' => 'Nomor Surat Kematian',
        'death_certificate_issuer' => 'Penerbit Surat Kematian',
        'death_certificate_date' => 'Tanggal Surat Kematian',
        'sppt_land_title' => 'SPPT',
        'letter_c_land_title' => 'Letter C',
        'transaction_amount' => 'Nilai Transaksi',
        'transaction_amount_wording' => 'Terbilang Nilai Transaksi',
        'area_of_the_land' => 'Luas Tanah',
        'area_of_the_land_wording' => 'Terbilang Luas Tanah',
        'pph' => 'PPh',
        'bphtb' => 'BPHTB',
        'adm' => 'Administrasi',
        'pbb' => 'PBB',
        'adm_certificate' => 'Administrasi Sertifikat',
        'ppat_amount' => 'Biaya PPAT',
        'total_amount' => 'Total Biaya',
        'status' => 'Status',
        'paid_amount' => 'Jumlah Dibayar',
        'remaining_amount' => 'Sisa Pembayaran',
        'north_border' => 'Batas Utara',
        'east_border' => 'Batas Timur',
        'west_border' => 'Batas Barat',
        'south_border' => 'Batas Selatan',
        'created_by' => 'Dibuat Oleh',
        'created_at' => 'Dibuat Pada',
        'applicants' => 'Pemohon',
        'applicant' => 'Nama Pemohon',
        'applicant_type' => 'Jenis Pemohon',
    ],

    'placeholders' => [
        'auto_generated' => 'Otomatis dibuat saat menyimpan',
        'land_title_type' => 'Pilih jenis surat tanah',
        'heir_from_name' => 'Masukkan nama almarhum',
        'death_place' => 'Masukkan tempat meninggal',
        'death_date' => 'Pilih tanggal meninggal',
        'death_certificate_number' => 'Masukkan nomor surat kematian',
        'death_certificate_issuer' => 'Masukkan instansi penerbit',
        'death_certificate_date' => 'Pilih tanggal surat kematian',
        'sppt_land_title' => 'Pilih SPPT (opsional)',
        'letter_c_land_title' => 'Pilih Letter C (opsional)',
        'transaction_amount' => 'Masukkan nilai transaksi',
        'area_of_the_land' => 'Masukkan luas tanah',
        'pph' => 'Masukkan PPh',
        'bphtb' => 'Masukkan BPHTB',
        'adm' => 'Masukkan biaya administrasi',
        'pbb' => 'Masukkan PBB',
        'adm_certificate' => 'Masukkan biaya administrasi sertifikat',
        'ppat_amount' => 'Masukkan biaya PPAT',
        'north_border' => 'Deskripsikan batas utara',
        'east_border' => 'Deskripsikan batas timur',
        'west_border' => 'Deskripsikan batas barat',
        'south_border' => 'Deskripsikan batas selatan',
        'applicant' => 'Pilih pemohon',
        'applicant_type' => 'Pilih jenis pemohon',
    ],

    'helpers' => [
        'land_title_number' => 'Format: 1/2025 (otomatis dibuat)',
        'is_heir' => 'Aktifkan jika surat tanah ini untuk ahli waris atau kasus warisan',
        'sppt_land_title' => 'Referensi ke dokumen SPPT jika tersedia',
        'letter_c_land_title' => 'Referensi ke dokumen Letter C jika tersedia',
        'transaction_amount' => 'Terbilang akan dibuat otomatis',
        'pph' => 'Otomatis dihitung 2,5% dari nilai transaksi',
        'ppat_amount' => 'Default 2% dari nilai transaksi, dapat diubah',
        'status' => 'Status akan diubah otomatis saat pembayaran atau penyelesaian',
    ],

    'filters' => [
        'type' => 'Jenis Surat',
        'created_from' => 'Dibuat Dari',
        'created_until' => 'Dibuat Sampai',
        'amount_from' => 'Nilai Dari',
        'amount_until' => 'Nilai Sampai',
    ],

    'actions' => [
        'add_applicant' => 'Tambah Pemohon',
        'generate_document' => 'Generate Dokumen',
        'payment' => 'Pembayaran',
        'complete' => 'Selesaikan',
        'withdrawal' => 'Tarik Dana',
    ],

    'status' => [
        'pending' => 'Menunggu',
        'paid' => 'Dibayar',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ],

    'payment' => [
        'remaining_amount' => 'Sisa Pembayaran',
        'amount' => 'Jumlah Pembayaran',
        'amount_helper' => 'Masukkan jumlah yang akan dibayarkan',
        'payment_date' => 'Tanggal Pembayaran',
        'notes' => 'Catatan',
        'notes_placeholder' => 'Tambahkan catatan pembayaran (opsional)',
        'deposit_description' => 'Pembayaran Surat Tanah :number',
    ],

    'complete' => [
        'completion_info' => 'Informasi Penyelesaian',
        'completion_number' => 'Nomor Penyelesaian',
        'completion_number_placeholder' => 'Opsional',
        'completion_year' => 'Tahun Penyelesaian',
        'completion_year_placeholder' => 'Opsional',
        'adm_distribution' => 'Distribusi Administrasi',
        'adm_distribution_desc' => 'Total Administrasi: Rp :amount - Pilih penerima dan alokasikan jumlahnya',
        'ppat_distribution' => 'Distribusi Biaya PPAT',
        'ppat_distribution_desc' => 'Total Biaya PPAT: Rp :amount - Pilih penerima dan alokasikan persentasenya',
        'user' => 'Pengguna',
        'amount' => 'Jumlah',
        'percentage' => 'Persentase',
        'add_recipient' => 'Tambah Penerima',
    ],

    'withdrawal' => [
        'description' => 'Penarikan dana - Surat Tanah :number dibatalkan',
    ],

    'errors' => [
        'no_buyer' => 'Tidak ada pembeli yang ditemukan pada surat tanah ini',
        'adm_total_mismatch' => 'Total distribusi administrasi (Rp :actual) harus sama dengan total administrasi (Rp :expected)',
        'ppat_percentage_mismatch' => 'Total persentase PPAT (:actual%) harus sama dengan 100%',
        'insufficient_balance' => 'Saldo pembeli tidak mencukupi. Saldo: Rp :balance, Dibutuhkan: Rp :needed',
    ],

    'notifications' => [
        'document_generated' => 'Dokumen berhasil dibuat',
        'document_generation_failed' => 'Gagal membuat dokumen',
        'payment_success' => 'Pembayaran Berhasil',
        'payment_success_body' => 'Pembayaran sebesar Rp :amount telah diterima',
        'payment_failed' => 'Pembayaran Gagal',
        'complete_success' => 'Surat Tanah Selesai',
        'complete_failed' => 'Penyelesaian Gagal',
        'withdrawal_success' => 'Penarikan Berhasil',
        'withdrawal_success_body' => 'Dana sebesar Rp :amount telah ditarik kembali',
        'withdrawal_failed' => 'Penarikan Gagal',
    ],

    'modals' => [
        'generate_document' => [
            'heading' => 'Generate Dokumen Akta',
            'description' => 'Apakah Anda yakin ingin membuat dokumen untuk surat tanah ini?',
            'submit' => 'Ya, Generate',
            'cancel' => 'Batal',
        ],
        'payment' => [
            'heading' => 'Terima Pembayaran',
            'submit' => 'Terima Pembayaran',
        ],
        'complete' => [
            'heading' => 'Selesaikan Surat Tanah',
            'submit' => 'Selesaikan',
        ],
        'withdrawal' => [
            'heading' => 'Tarik Dana & Batalkan',
            'description' => 'Anda akan menarik dana sebesar Rp :amount dari dompet pembeli dan membatalkan surat tanah ini. Tindakan ini tidak dapat dibatalkan.',
            'submit' => 'Ya, Tarik Dana',
        ],
    ],

    'columns' => [
        'land_title_number' => 'No. Surat',
    ],

    'types' => [
        'navigation_label' => 'Jenis Surat Tanah',
        'model_label' => 'Jenis Surat Tanah',
        'plural_model_label' => 'Jenis Surat Tanah',
        'fields' => [
            'name' => 'Nama Jenis',
            'usage_count' => 'Digunakan',
            'created_at' => 'Dibuat Pada',
        ],
        'placeholders' => [
            'name' => 'Contoh: Jual Beli, Hibah, Waris',
        ],
    ],

    'applicant_types' => [
        'navigation_label' => 'Jenis Pemohon',
        'model_label' => 'Jenis Pemohon',
        'plural_model_label' => 'Jenis Pemohon',
        'fields' => [
            'name' => 'Nama Jenis',
            'usage_count' => 'Digunakan',
            'created_at' => 'Dibuat Pada',
        ],
        'placeholders' => [
            'name' => 'Contoh: Penjual, Pembeli, Saksi',
        ],
    ],
];
