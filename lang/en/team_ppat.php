<?php

return [
    'navigation_label' => 'Team PPAT',
    'model_label' => 'Team Member',
    'plural_model_label' => 'Team PPAT',

    'sections' => [
        'personal_information' => 'Personal Information',
        'address' => 'Address',
        'password' => 'Password',
    ],

    'fields' => [
        'name' => 'Full Name',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'birthdate' => 'Date of Birth',
        'birthplace' => 'Place of Birth',
        'occupation' => 'Occupation',
        'national_id_number' => 'National ID Number (KTP)',
        'road' => 'Street/Road',
        'rt' => 'RT',
        'rw' => 'RW',
        'village' => 'Village (Kelurahan/Desa)',
        'district' => 'District (Kecamatan)',
        'city' => 'City (Kota/Kabupaten)',
        'province' => 'Province',
        'password' => 'Password',
    ],

    'placeholders' => [
        'name' => 'Enter full name',
        'email' => 'Enter email address',
        'phone' => 'Enter phone number',
        'birthdate' => 'Select date of birth',
        'birthplace' => 'Enter place of birth',
        'occupation' => 'Enter occupation',
        'national_id_number' => 'Enter KTP number',
        'road' => 'Enter street/road name',
        'rt' => 'Enter RT number',
        'rw' => 'Enter RW number',
        'village' => 'Enter village name',
        'district' => 'Enter district name',
        'city' => 'Enter city name',
        'province' => 'Enter province name',
        'password' => 'Enter password',
    ],

    'helpers' => [
        'password' => 'Leave blank to keep current password',
    ],

    'columns' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'city' => 'City',
        'occupation' => 'Occupation',
        'balance' => 'Wallet Balance',
        'created_at' => 'Created At',
    ],

    'filters' => [
        'has_phone' => 'Has Phone Number',
        'created_from' => 'Created From',
        'created_until' => 'Created Until',
    ],

    'actions' => [
        'deposit' => 'Deposit',
        'withdrawal' => 'Withdrawal',
    ],

    'wallet' => [
        'current_balance' => 'Current Balance',
        'deposit_amount' => 'Deposit Amount',
        'withdrawal_amount' => 'Withdrawal Amount',
        'description' => 'Description',
        'description_placeholder' => 'Enter transaction description (optional)',
        'deposit_helper' => 'Enter amount to deposit to wallet',
        'withdrawal_helper' => 'Enter amount to withdraw (allows negative balance)',
        'manual_deposit' => 'Manual deposit',
        'manual_withdrawal' => 'Manual withdrawal',
    ],

    'modals' => [
        'deposit' => [
            'heading' => 'Deposit to Wallet',
            'submit' => 'Deposit',
        ],
        'withdrawal' => [
            'heading' => 'Withdraw from Wallet',
            'description' => 'Force withdrawal allows negative balance. Withdraw any amount regardless of current balance.',
            'submit' => 'Withdraw',
        ],
    ],

    'notifications' => [
        'deposit_success' => 'Deposit Successful',
        'deposit_success_body' => 'Successfully deposited Rp :amount to :name wallet',
        'deposit_failed' => 'Deposit Failed',
        'withdrawal_success' => 'Withdrawal Successful',
        'withdrawal_success_body' => 'Successfully withdrawn Rp :amount from :name wallet',
        'withdrawal_failed' => 'Withdrawal Failed',
        'negative_balance_warning' => 'Warning: Negative balance -Rp :balance',
    ],

    'errors' => [
        'insufficient_balance' => 'Insufficient balance. Current balance: Rp :balance',
    ],
];
