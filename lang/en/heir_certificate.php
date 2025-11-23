<?php

return [
    // Navigation & Model
    'navigation' => 'Heir Certificates',
    'model_label' => 'Heir Certificate',
    'plural_model_label' => 'Heir Certificates',

    // Pages
    'pages' => [
        'list' => 'List Heir Certificates',
        'create' => 'Create Heir Certificate',
        'edit' => 'Edit Heir Certificate',
        'view' => 'View Heir Certificate',
    ],

    // Sections
    'sections' => [
        'certificate_information' => [
            'title' => 'Certificate Information',
            'description' => 'Enter the basic information for the heir certificate',
        ],
        'deceased_information' => [
            'title' => 'Deceased Information',
            'description' => 'Enter information about the deceased person',
        ],
        'heirs_information' => [
            'title' => 'Heirs Information',
            'description' => 'Add all heirs for this certificate (minimum 1 heir required)',
        ],
    ],

    // Fields
    'fields' => [
        'certificate_number' => 'Certificate Number',
        'certificate_date' => 'Certificate Date',
        'year' => 'Year',
        'applicant_name' => 'Applicant Name',
        'applicant_address' => 'Applicant Address',
        'phone_number' => 'Phone Number',
        'deceased_name' => 'Deceased Name',
        'date_of_death' => 'Date of Death',
        'place_of_death' => 'Place of Death',
        'person_in_charge' => 'Person In Charge (PIC)',
        'status' => 'Status',
        'notes' => 'Notes',
        'created_by' => 'Created By',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'heirs_count' => 'Heirs',
    ],

    // Field Helpers
    'helpers' => [
        'certificate_number' => 'Format: 1/2025 (auto-generated)',
        'certificate_date' => 'The date this certificate was issued',
        'phone_number' => 'Indonesian phone format (08xxx or 62xxx). Will be auto-formatted to 62xxx',
        'status' => 'Current certificate status',
        'person_in_charge' => 'Select the person responsible for handling this certificate',
        'date_of_death' => 'The date the person passed away',
    ],

    // Placeholders
    'placeholders' => [
        'certificate_number' => 'Auto-generated on save',
        'applicant_name' => 'Enter applicant full name',
        'applicant_address' => 'Enter complete applicant address',
        'phone_number' => '08xxx or 62xxx',
        'deceased_name' => 'Enter deceased full name',
        'place_of_death' => 'Enter place of death',
        'person_in_charge' => 'Select person responsible for this certificate',
        'heir_name' => 'Enter heir full name',
        'heir_address' => 'Enter heir address (optional)',
        'relationship' => 'e.g., Son, Daughter, Spouse, etc.',
    ],

    // Heirs Section
    'heirs' => [
        'section_title' => 'Heirs Information',
        'name' => 'Heir Name',
        'relationship' => 'Relationship to Deceased',
        'id_number' => 'ID Number',
        'address' => 'Heir Address',
        'add_button' => 'Add Heir',
        'new_heir' => 'New Heir',
        'relationship_helper' => 'Relationship with the deceased person',
    ],

    // Table Columns
    'columns' => [
        'certificate_no' => 'Certificate No.',
        'status' => 'Status',
        'certificate_date' => 'Certificate Date',
        'applicant' => 'Applicant',
        'phone_number' => 'Phone Number',
        'deceased' => 'Deceased',
        'date_of_death' => 'Date of Death',
        'place_of_death' => 'Place of Death',
        'heirs' => 'Heirs',
        'person_in_charge' => 'Person In Charge',
        'created_by' => 'Created By',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'year' => 'Year',
        'person_in_charge' => 'Person In Charge',
        'created_by' => 'Created By',
        'certificate_date' => 'Certificate Date',
        'date_of_death' => 'Date of Death',
        'certificate_from' => 'Certificate From',
        'certificate_until' => 'Certificate Until',
        'death_from' => 'Death From',
        'death_until' => 'Death Until',
        'all_statuses' => 'All Statuses',
        'all_years' => 'All Years',
        'all_users' => 'All Users',
    ],

    // Filter Indicators
    'filter_indicators' => [
        'certificate_from' => 'Certificate from :date',
        'certificate_until' => 'Certificate until :date',
        'death_from' => 'Death from :date',
        'death_until' => 'Death until :date',
    ],

    // Actions
    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'actions' => 'Actions',
        'send_whatsapp' => 'Send WhatsApp Message',
        'mark_completed' => 'Mark as Completed',
        'mark_on_progress' => 'Mark as On Progress',
        'export_selected' => 'Export Selected',
        'create' => 'Create',
    ],

    // WhatsApp Form
    'whatsapp' => [
        'select_campaign' => 'Select Campaign',
        'phone_number' => 'Phone Number',
        'phone_number_placeholder' => '08xxxxxxxxxx or 628xxxxxxxxxx',
        'phone_number_helper' => 'Format: 08xxxxxxxxxx or 628xxxxxxxxxx',
        'message_preview' => 'Message Preview',
        'no_preview' => 'No preview available',
        'campaign_not_found' => 'Campaign not found',
        'modal_heading' => 'Send WhatsApp Message',
        'modal_description' => 'Send a WhatsApp notification about this certificate',
        'modal_submit' => 'Send Message',
        'phone_not_set_tooltip' => 'Phone number not set for this certificate',
    ],

    // Notifications
    'notifications' => [
        'created' => 'Certificate created successfully.',
        'updated' => 'Certificate updated successfully.',
        'deleted' => 'Certificate deleted successfully.',
        'whatsapp_sent' => 'WhatsApp message sent successfully to :phone',
        'whatsapp_failed' => 'Failed to send WhatsApp message',
        'campaign_not_found' => 'The selected campaign could not be found.',
        'status_updated' => 'Status Updated',
        'marked_completed' => 'Certificate :number marked as completed.',
        'marked_on_progress' => 'Certificate :number marked as on progress.',
        'message_sent' => 'Message Sent',
        'failed_to_send' => 'Failed to Send',
    ],

    // Modal Confirmations
    'modals' => [
        'mark_completed' => [
            'heading' => 'Mark Certificate as Completed',
            'description' => 'Are you sure you want to mark this certificate as completed?',
            'submit' => 'Mark as Completed',
        ],
        'mark_on_progress' => [
            'heading' => 'Mark Certificate as On Progress',
            'description' => 'Are you sure you want to mark this certificate as on progress?',
            'submit' => 'Mark as On Progress',
        ],
    ],

    // Table Messages
    'table' => [
        'empty_state_heading' => 'No heir certificates yet',
        'empty_state_description' => 'Create your first heir certificate to get started.',
        'phone_copied' => 'Phone number copied!',
        'not_provided' => 'Not provided',
        'not_assigned' => 'Not assigned',
    ],

    // Export
    'export' => [
        'certificate_number' => 'Certificate Number',
        'certificate_date' => 'Certificate Date',
        'status' => 'Status',
        'applicant_name' => 'Applicant Name',
        'applicant_address' => 'Applicant Address',
        'phone_number' => 'Phone Number',
        'deceased_name' => 'Deceased Name',
        'place_of_death' => 'Place of Death',
        'date_of_death' => 'Date of Death',
        'person_in_charge' => 'Person In Charge',
        'heirs_count' => 'Number of Heirs',
        'heirs_names' => 'Heirs Names',
        'created_by' => 'Created By',
        'created_at' => 'Created At',
    ],

    // Global Search
    'global_search' => [
        'certificate_date' => 'Certificate Date',
        'deceased' => 'Deceased',
        'heirs' => ':count heir(s)',
    ],
];
