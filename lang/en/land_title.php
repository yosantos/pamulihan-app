<?php

return [
    'navigation_label' => 'Land Titles',
    'model_label' => 'Land Title',
    'plural_model_label' => 'Land Titles',

    'sections' => [
        'land_title_information' => 'Land Title Information',
        'transaction_details' => 'Transaction Details',
        'fees_and_taxes' => 'Fees & Taxes',
        'border_information' => 'Border Information',
        'applicants' => 'Applicants / Related Parties',
    ],

    'fields' => [
        'land_title_number' => 'Land Title Number',
        'land_title_type' => 'Land Title Type',
        'sppt_land_title' => 'SPPT',
        'letter_c_land_title' => 'Letter C',
        'transaction_amount' => 'Transaction Amount',
        'transaction_amount_wording' => 'Transaction Amount in Words',
        'area_of_the_land' => 'Land Area',
        'area_of_the_land_wording' => 'Land Area in Words',
        'pph' => 'Income Tax (PPh)',
        'bphtb' => 'BPHTB',
        'adm' => 'Administration',
        'pbb' => 'Property Tax (PBB)',
        'adm_certificate' => 'Certificate Administration',
        'ppat_amount' => 'PPAT Fee',
        'total_amount' => 'Total Amount',
        'status' => 'Status',
        'paid_amount' => 'Paid Amount',
        'remaining_amount' => 'Remaining Payment',
        'north_border' => 'North Border',
        'east_border' => 'East Border',
        'west_border' => 'West Border',
        'south_border' => 'South Border',
        'created_by' => 'Created By',
        'created_at' => 'Created At',
        'applicants' => 'Applicants',
        'applicant' => 'Applicant Name',
        'applicant_type' => 'Applicant Type',
    ],

    'placeholders' => [
        'auto_generated' => 'Auto-generated on save',
        'land_title_type' => 'Select land title type',
        'sppt_land_title' => 'Select SPPT (optional)',
        'letter_c_land_title' => 'Select Letter C (optional)',
        'transaction_amount' => 'Enter transaction amount',
        'area_of_the_land' => 'Enter land area',
        'pph' => 'Enter income tax',
        'bphtb' => 'Enter BPHTB',
        'adm' => 'Enter administration fee',
        'pbb' => 'Enter property tax',
        'adm_certificate' => 'Enter certificate administration fee',
        'ppat_amount' => 'Enter PPAT fee',
        'north_border' => 'Describe north border',
        'east_border' => 'Describe east border',
        'west_border' => 'Describe west border',
        'south_border' => 'Describe south border',
        'applicant' => 'Select applicant',
        'applicant_type' => 'Select applicant type',
    ],

    'helpers' => [
        'land_title_number' => 'Format: 1/2025 (auto-generated)',
        'sppt_land_title' => 'Reference to SPPT document if available',
        'letter_c_land_title' => 'Reference to Letter C document if available',
        'transaction_amount' => 'Amount in words will be generated automatically',
        'pph' => 'Automatically calculated as 2.5% of transaction amount',
        'ppat_amount' => 'Default 2% of transaction amount, can be edited',
        'status' => 'Status will be automatically changed on payment or completion',
    ],

    'filters' => [
        'type' => 'Type',
        'created_from' => 'Created From',
        'created_until' => 'Created Until',
        'amount_from' => 'Amount From',
        'amount_until' => 'Amount Until',
    ],

    'actions' => [
        'add_applicant' => 'Add Applicant',
        'generate_document' => 'Generate Document',
        'payment' => 'Payment',
        'complete' => 'Complete',
        'withdrawal' => 'Withdrawal',
    ],

    'status' => [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'payment' => [
        'remaining_amount' => 'Remaining Payment',
        'amount' => 'Payment Amount',
        'amount_helper' => 'Enter the amount to be paid',
        'payment_date' => 'Payment Date',
        'notes' => 'Notes',
        'notes_placeholder' => 'Add payment notes (optional)',
        'deposit_description' => 'Land Title Payment :number',
    ],

    'complete' => [
        'completion_info' => 'Completion Information',
        'completion_number' => 'Completion Number',
        'completion_number_placeholder' => 'Optional',
        'completion_year' => 'Completion Year',
        'completion_year_placeholder' => 'Optional',
        'adm_distribution' => 'Administration Distribution',
        'adm_distribution_desc' => 'Total Administration: Rp :amount - Select recipients and allocate amounts',
        'ppat_distribution' => 'PPAT Fee Distribution',
        'ppat_distribution_desc' => 'Total PPAT Fee: Rp :amount - Select recipients and allocate percentages',
        'user' => 'User',
        'amount' => 'Amount',
        'percentage' => 'Percentage',
        'add_recipient' => 'Add Recipient',
    ],

    'withdrawal' => [
        'description' => 'Withdrawal - Land Title :number cancelled',
    ],

    'errors' => [
        'no_buyer' => 'No buyer found for this land title',
        'adm_total_mismatch' => 'Administration distribution total (Rp :actual) must equal total administration (Rp :expected)',
        'ppat_percentage_mismatch' => 'PPAT percentage total (:actual%) must equal 100%',
        'insufficient_balance' => 'Buyer has insufficient balance. Balance: Rp :balance, Needed: Rp :needed',
    ],

    'notifications' => [
        'document_generated' => 'Document generated successfully',
        'document_generation_failed' => 'Failed to generate document',
        'payment_success' => 'Payment Successful',
        'payment_success_body' => 'Payment of Rp :amount has been received',
        'payment_failed' => 'Payment Failed',
        'complete_success' => 'Land Title Completed',
        'complete_failed' => 'Completion Failed',
        'withdrawal_success' => 'Withdrawal Successful',
        'withdrawal_success_body' => 'Amount of Rp :amount has been withdrawn',
        'withdrawal_failed' => 'Withdrawal Failed',
    ],

    'modals' => [
        'generate_document' => [
            'heading' => 'Generate Deed Document',
            'description' => 'Are you sure you want to generate the document for this land title?',
            'submit' => 'Yes, Generate',
            'cancel' => 'Cancel',
        ],
        'payment' => [
            'heading' => 'Receive Payment',
            'submit' => 'Receive Payment',
        ],
        'complete' => [
            'heading' => 'Complete Land Title',
            'submit' => 'Complete',
        ],
        'withdrawal' => [
            'heading' => 'Withdraw & Cancel',
            'description' => 'You will withdraw Rp :amount from the buyer\'s wallet and cancel this land title. This action cannot be undone.',
            'submit' => 'Yes, Withdraw',
        ],
    ],

    'columns' => [
        'land_title_number' => 'No.',
    ],

    'types' => [
        'navigation_label' => 'Land Title Types',
        'model_label' => 'Land Title Type',
        'plural_model_label' => 'Land Title Types',
        'fields' => [
            'name' => 'Type Name',
            'usage_count' => 'Usage Count',
            'created_at' => 'Created At',
        ],
        'placeholders' => [
            'name' => 'Example: Sale and Purchase, Grant, Inheritance',
        ],
    ],

    'applicant_types' => [
        'navigation_label' => 'Applicant Types',
        'model_label' => 'Applicant Type',
        'plural_model_label' => 'Applicant Types',
        'fields' => [
            'name' => 'Type Name',
            'usage_count' => 'Usage Count',
            'created_at' => 'Created At',
        ],
        'placeholders' => [
            'name' => 'Example: Seller, Buyer, Witness',
        ],
    ],
];
