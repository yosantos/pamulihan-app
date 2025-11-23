<?php

return [
    // Navigation & Model
    'navigation' => 'Users',
    'navigation_group' => 'User Management',
    'model_label' => 'User',
    'plural_model_label' => 'Users',

    // Pages
    'pages' => [
        'list' => 'List Users',
        'create' => 'Create User',
        'edit' => 'Edit User',
        'view' => 'View User',
    ],

    // Sections
    'sections' => [
        'user_information' => [
            'title' => 'User Information',
        ],
        'personal_information' => [
            'title' => 'Personal Information',
            'description' => 'User personal information including national ID, birthplace, birthdate, and occupation',
        ],
        'address_information' => [
            'title' => 'Address Information',
            'description' => 'Complete user address including province, city, district, village, road, RT and RW',
        ],
        'avatar' => [
            'title' => 'Avatar',
        ],
        'documents' => [
            'title' => 'Documents',
        ],
        'roles_permissions' => [
            'title' => 'Roles & Permissions',
        ],
    ],

    // Fields
    'fields' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone Number',
        'password' => 'Password',
        'national_id_number' => 'National ID Number',
        'birthplace' => 'Birthplace',
        'birthdate' => 'Date of Birth',
        'occupation' => 'Occupation',
        'province' => 'Province',
        'city' => 'City/Regency',
        'district' => 'District',
        'village' => 'Village',
        'road' => 'Road/Street',
        'rt' => 'RT',
        'rw' => 'RW',
        'avatar' => 'Avatar',
        'documents' => 'Documents',
        'roles' => 'Roles',
        'balance' => 'Wallet Balance',
        'created_at' => 'Created At',
    ],

    // Field Helpers
    'helpers' => [
        'phone' => 'Format: 628xxxxxxxxxx or 08xxxxxxxxxx',
        'national_id_number' => 'Indonesian National ID Number consists of 16 digits',
        'rt' => 'RT (Rukun Tetangga) number',
        'rw' => 'RW (Rukun Warga) number',
        'avatar' => 'Upload user avatar (max 2MB)',
        'documents' => 'Upload user documents (max 5MB each, up to 10 files)',
    ],

    // Placeholders
    'placeholders' => [
        'phone_not_available' => 'N/A',
        'national_id_number' => '1234567890123456',
        'birthplace' => 'Jakarta',
        'birthdate' => 'Select birthdate',
        'occupation' => 'Entrepreneur',
        'province' => 'DKI Jakarta',
        'city' => 'South Jakarta',
        'district' => 'Kebayoran Baru',
        'village' => 'Gunung',
        'road' => 'Jl. Raya Kebayoran No. 123',
        'rt' => '001',
        'rw' => '002',
    ],

    // Table Columns
    'columns' => [
        'avatar' => 'Avatar',
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone Number',
        'national_id_number' => 'National ID Number',
        'birthdate' => 'Date of Birth',
        'balance' => 'Wallet Balance',
        'roles' => 'Roles',
        'created_at' => 'Created At',
    ],

    // Filters
    'filters' => [
        'roles' => 'Roles',
    ],

    // Actions
    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'add_balance' => 'Add Balance',
        'deduct_balance' => 'Deduct Balance',
        'transfer_balance' => 'Transfer Balance',
        'send_whatsapp' => 'Send WhatsApp',
    ],

    // Wallet Actions
    'wallet' => [
        'add_balance' => [
            'label' => 'Add Balance',
            'amount' => 'Amount to Add',
            'description' => 'Description',
            'modal_heading' => 'Add Balance',
        ],
        'deduct_balance' => [
            'label' => 'Deduct Balance',
            'amount' => 'Amount to Deduct',
            'description' => 'Description',
            'modal_heading' => 'Deduct Balance',
        ],
        'transfer_balance' => [
            'label' => 'Transfer Balance',
            'recipient' => 'Transfer To',
            'amount' => 'Amount to Transfer',
            'description' => 'Description',
            'modal_heading' => 'Transfer Balance',
        ],
    ],

    // WhatsApp Action
    'whatsapp' => [
        'label' => 'Send WhatsApp',
        'phone_number' => 'Phone Number',
        'phone_helper' => 'Format: 628xxxxxxxxxx',
        'message' => 'Message',
        'message_placeholder' => 'Enter your message here...',
        'modal_heading' => 'Send WhatsApp',
    ],

    // Notifications
    'notifications' => [
        'balance_added' => [
            'title' => 'Balance Added',
            'body' => 'IDR :amount has been added to :name\'s wallet.',
        ],
        'balance_deducted' => [
            'title' => 'Balance Deducted',
            'body' => 'IDR :amount has been deducted from :name\'s wallet.',
        ],
        'insufficient_balance' => [
            'title' => 'Insufficient Balance',
            'body' => 'User does not have enough balance. Current balance: IDR :balance',
        ],
        'transfer_successful' => [
            'title' => 'Transfer Successful',
            'body' => 'IDR :amount has been transferred from :from to :to.',
        ],
        'whatsapp_sent' => [
            'title' => 'WhatsApp Sent',
            'body' => 'Message has been sent to :name.',
        ],
        'whatsapp_error' => [
            'title' => 'WhatsApp Error',
            'body' => ':message',
        ],
        'invalid_phone' => [
            'title' => 'Invalid Phone Number',
            'body' => 'Please provide a valid Indonesian phone number.',
        ],
    ],

    // Relation Managers
    'relations' => [
        'transactions' => 'Transactions',
    ],

    // Bulk Actions
    'bulk_actions' => [
        'delete' => 'Delete Selected',
    ],
];
