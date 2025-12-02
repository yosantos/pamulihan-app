<?php

return [
    // Navigation & Model
    'navigation' => 'Family ID Cards',
    'model_label' => 'Family ID Card',
    'plural_model_label' => 'Family ID Cards',

    // Pages
    'pages' => [
        'list' => 'List Family ID Cards',
        'create' => 'Create Family ID Card',
        'edit' => 'Edit Family ID Card',
        'view' => 'View Family ID Card',
    ],

    // Sections
    'sections' => [
        'registration_information' => [
            'title' => 'Registration Information',
            'description' => 'Enter the registration details for the family ID card application',
        ],
        'applicant_information' => [
            'title' => 'Applicant Information',
            'description' => 'Enter the applicant personal information',
        ],
        'admin_section' => [
            'title' => 'Administrative Notes',
            'description' => 'Internal notes and administrative information',
        ],
        'whatsapp_preview' => [
            'title' => 'WhatsApp Notification Preview',
            'description' => 'Preview of the WhatsApp message that will be sent to the applicant upon registration',
            'label' => 'Message Preview',
            'no_campaign' => 'No campaign configured. Please configure a registration campaign in Family ID Card Settings.',
            'campaign_not_found' => 'Configured campaign not found.',
        ],
    ],

    // Fields
    'fields' => [
        'no_registration' => 'Registration Number',
        'name' => 'Applicant Name',
        'date' => 'Registration Date',
        'due_date' => 'Due Date',
        'national_id_number' => 'National ID Number',
        'address' => 'Address',
        'village' => 'Village',
        'phone_number' => 'Phone Number',
        'note' => 'Notes',
        'status' => 'Status',
        'admin_memo' => 'Admin Memo',
        'rejection_reason' => 'Rejection Reason',
        'person_in_charge' => 'Person In Charge (PIC)',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],

    // Field Helpers
    'helpers' => [
        'no_registration' => 'Format: 001/2025 (auto-generated)',
        'date' => 'The date when applicant came to register',
        'due_date' => 'Automatically calculated as registration date + 7 days',
        'national_id_number' => 'Applicant national ID card number (NIK)',
        'phone_number' => 'Indonesian phone format (08xxx or 62xxx). Will be auto-formatted to 62xxx',
        'status' => 'Current application status',
        'person_in_charge' => 'Select the person responsible for handling this application',
        'admin_memo' => 'Internal administrative notes (not visible to applicant)',
    ],

    // Placeholders
    'placeholders' => [
        'no_registration' => 'Auto-generated on save',
        'name' => 'Enter applicant full name',
        'national_id_number' => 'Enter 16-digit NIK',
        'address' => 'Enter complete address',
        'village' => 'Select village',
        'phone_number' => '08xxx or 62xxx',
        'note' => 'Additional notes or special requests',
        'admin_memo' => 'Internal administrative notes',
        'rejection_reason' => 'Reason for rejection',
        'person_in_charge' => 'Select person responsible for this application',
    ],

    // Table Columns
    'columns' => [
        'no_registration' => 'Registration No.',
        'status' => 'Status',
        'name' => 'Applicant Name',
        'date' => 'Registration Date',
        'due_date' => 'Due Date',
        'national_id_number' => 'NIK',
        'phone_number' => 'Phone Number',
        'village' => 'Village',
        'person_in_charge' => 'Person In Charge',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'person_in_charge' => 'Person In Charge',
        'village' => 'Village',
        'date_from' => 'Registration From',
        'date_until' => 'Registration Until',
        'overdue' => 'Overdue Applications',
        'all_statuses' => 'All Statuses',
        'all_users' => 'All Users',
        'all_villages' => 'All Villages',
    ],

    // Filter Indicators
    'filter_indicators' => [
        'date_from' => 'Registration from :date',
        'date_until' => 'Registration until :date',
    ],

    // Actions
    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'actions' => 'Actions',
        'reject' => 'Reject Application',
        'mark_completed' => 'Mark as Completed',
        'mark_on_progress' => 'Mark as On Progress',
        'create' => 'Create',
        'send_whatsapp' => 'Send WhatsApp',
    ],

    // Create WhatsApp Notification
    'create' => [
        'notification_section' => 'WhatsApp Notification (Optional)',
        'notification_description' => 'Send a WhatsApp notification to the applicant after registration',
        'applicant_info' => 'Applicant Information',
        'applicant_details' => 'Name: :name | Phone: :phone',
        'select_campaign' => 'Select WhatsApp Campaign',
        'campaign_placeholder' => 'Choose a campaign template (optional)',
        'campaign_helper' => 'Select a WhatsApp campaign to automatically notify the applicant after registration. Leave empty to skip notification.',
        'message_preview' => 'Message Preview',
        'no_preview' => 'Select a campaign to see the message preview',
        'campaign_not_found' => 'Campaign not found',
        'send_notification' => 'Send WhatsApp Notification',
        'modal_heading' => 'Send Registration Notification',
        'modal_description' => 'Send a WhatsApp notification to the applicant about their family ID card registration',
        'modal_submit' => 'Send Notification',
        'modal_cancel' => 'Skip',
    ],

    // Reject Application
    'reject' => [
        'applicant_info' => 'Applicant Information',
        'applicant_details' => 'Name: :name | Phone: :phone',
        'reason' => 'Rejection Reason',
        'reason_placeholder' => 'Enter the reason for rejecting this application',
        'select_campaign' => 'Select Rejection Campaign',
        'campaign_placeholder' => 'Choose a rejection notification template',
        'message_preview' => 'Message Preview',
        'no_preview' => 'Select a campaign to see the message preview',
        'no_campaign_configured' => 'No rejection campaign configured. Please configure one in Family ID Card Settings.',
        'campaign_not_found' => 'Campaign not found',
        'modal_heading' => 'Reject Application',
        'modal_description' => 'Provide a reason for rejection and notify the applicant via WhatsApp',
        'modal_submit' => 'Reject & Send Notification',
    ],

    // Complete Application
    'complete' => [
        'applicant_info' => 'Applicant Information',
        'applicant_details' => 'Name: :name | Phone: :phone',
        'select_campaign' => 'Select Completion Campaign',
        'campaign_placeholder' => 'Choose a completion notification template',
        'message_preview' => 'Message Preview',
        'no_preview' => 'Select a campaign to see the message preview',
        'no_campaign_configured' => 'No completion campaign configured. Please configure one in Family ID Card Settings.',
        'campaign_not_found' => 'Campaign not found',
        'modal_heading' => 'Mark as Completed',
        'modal_description' => 'Mark this application as completed and notify the applicant via WhatsApp',
        'modal_submit' => 'Complete & Send Notification',
    ],

    // WhatsApp Resend
    'whatsapp' => [
        'applicant_info' => 'Applicant Information',
        'applicant_details' => 'Name: :name | Phone: :phone | Registration: :registration',
        'select_campaign' => 'Select WhatsApp Campaign',
        'campaign_placeholder' => 'Choose a campaign template',
        'message_preview' => 'Message Preview',
        'no_preview' => 'Select a campaign to see the message preview',
        'campaign_not_found' => 'Campaign not found',
        'modal_heading' => 'Send WhatsApp Message',
        'modal_description' => 'Send or resend a WhatsApp notification to the applicant',
        'modal_submit' => 'Send Message',
        'phone_not_set' => 'Phone number not available',
    ],

    // Notifications
    'notifications' => [
        'created' => 'Family ID card registration created successfully.',
        'updated' => 'Family ID card registration updated successfully.',
        'deleted' => 'Family ID card registration deleted successfully.',
        'notification_sent' => 'Notification Sent',
        'notification_failed' => 'Notification Failed',
        'whatsapp_sent' => 'WhatsApp notification sent successfully for registration :registration',
        'whatsapp_failed' => 'Failed to send WhatsApp notification',
        'whatsapp_failed_detail' => 'Failed to send notification: :error',
        'status_updated' => 'Status Updated',
        'marked_completed' => 'Application marked as completed.',
        'marked_rejected' => 'Application marked as rejected.',
        'marked_on_progress' => 'Application :registration marked as on progress.',
        'created_and_notified' => 'Registration Created & Notification Sent',
        'created_notification_failed' => 'Registration Created',
        'rejected_and_notified' => 'Application Rejected & Notification Sent',
        'rejection_sent' => 'Rejection notification sent for registration :registration',
        'rejected_notification_failed' => 'Application Rejected',
        'rejection_status_updated' => 'Application status updated to rejected, but notification was not sent.',
        'completed_and_notified' => 'Application Completed & Notification Sent',
        'completion_sent' => 'Completion notification sent for registration :registration',
        'completed_notification_failed' => 'Application Completed',
        'completion_status_updated' => 'Application status updated to completed, but notification was not sent.',
        'message_sent' => 'Message Sent Successfully',
        'failed_to_send' => 'Failed to Send Message',
        'campaign_not_found' => 'Campaign not found',
    ],

    // Modal Confirmations
    'modals' => [
        'mark_on_progress' => [
            'heading' => 'Mark as On Progress',
            'description' => 'Are you sure you want to mark this application as on progress?',
            'submit' => 'Mark as On Progress',
        ],
    ],

    // Table Messages
    'table' => [
        'empty_state_heading' => 'No family ID card registrations yet',
        'empty_state_description' => 'Create your first family ID card registration to get started.',
        'phone_copied' => 'Phone number copied!',
        'not_assigned' => 'Not assigned',
        'overdue' => 'Overdue',
    ],

    // Global Search
    'global_search' => [
        'registration_date' => 'Registration Date',
        'due_date' => 'Due Date',
        'status' => 'Status',
    ],
];
