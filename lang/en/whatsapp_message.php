<?php

return [
    // Navigation & Model
    'navigation' => 'WhatsApp Messages',
    'navigation_group' => 'Communication',
    'model_label' => 'WhatsApp Message',
    'plural_model_label' => 'WhatsApp Messages',

    // Pages
    'pages' => [
        'list' => 'List WhatsApp Messages',
        'create' => 'Send WhatsApp Message',
        'view' => 'View WhatsApp Message',
    ],

    // Sections
    'sections' => [
        'message_details' => [
            'title' => 'Message Details',
        ],
        'campaign_information' => [
            'title' => 'Campaign Information',
        ],
        'campaign_variables' => [
            'title' => 'Campaign Variables',
        ],
        'status_information' => [
            'title' => 'Status Information',
        ],
        'user_tracking' => [
            'title' => 'User Tracking',
        ],
    ],

    // Fields
    'fields' => [
        'use_campaign' => 'Use Campaign Template',
        'campaign' => 'Select Campaign',
        'phone_number' => 'Phone Number',
        'message' => 'Message Body',
        'status' => 'Status',
        'retry_count' => 'Retry Count',
        'error_message' => 'Error Message',
        'sent_at' => 'Sent At',
        'created_by' => 'Created By',
        'sent_by' => 'Sent By',
        'campaign_name' => 'Campaign Name',
        'variables_used' => 'Variables Used',
        'original_template' => 'Original Template',
        'created_at' => 'Created At',
    ],

    // Field Helpers
    'helpers' => [
        'use_campaign' => 'Toggle to send message using a campaign template',
        'phone_number' => 'Format: 08xxxxxxxxxx or 628xxxxxxxxxx',
        'character_count' => 'Characters: :count / 1000',
        'retry_count' => 'Number of times this message has been retried',
        'created_by_helper' => 'User who created this message',
        'sent_by_helper' => 'User who sent or last resent this message',
    ],

    // Placeholders
    'placeholders' => [
        'phone_number' => '08xxxxxxxxxx or 628xxxxxxxxxx',
        'message' => 'Enter your WhatsApp message here...',
        'select_campaign' => 'Select a campaign to see details',
        'manual_message' => 'Manual Message',
        'not_available' => 'N/A',
        'variable_value' => 'Enter value for [:variable]',
    ],

    // Campaign Info
    'campaign_info' => [
        'select_to_see_details' => 'Select a campaign to see details',
        'not_found' => 'Campaign not found',
        'company' => 'Company',
        'description' => 'Description',
        'required_variables' => 'Required Variables',
        'no_variables' => 'No variables used',
    ],

    // Message Preview
    'preview' => [
        'label' => 'Message Preview',
        'no_preview' => 'No preview available',
    ],

    // Table Columns
    'columns' => [
        'phone_number' => 'Phone Number',
        'message_preview' => 'Message Preview',
        'campaign' => 'Campaign',
        'status' => 'Status',
        'retries' => 'Retries',
        'created_by' => 'Created By',
        'sent_by' => 'Sent By',
        'sent_at' => 'Sent At',
        'created_at' => 'Created At',
        'manual' => 'Manual',
    ],

    // Status
    'status' => [
        'sent' => 'Sent',
        'failed' => 'Failed',
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'message_type' => 'Message Type',
        'all_messages' => 'All messages',
        'campaign_messages' => 'Campaign messages',
        'manual_messages' => 'Manual messages',
        'sent_from' => 'Sent From',
        'sent_until' => 'Sent Until',
    ],

    // Actions
    'actions' => [
        'view' => 'View',
        'resend' => 'Resend',
        'resend_selected' => 'Resend Selected',
    ],

    // Resend Modal
    'resend' => [
        'heading' => 'Resend WhatsApp Message',
        'description' => 'Are you sure you want to resend this message to :phone?',
        'submit' => 'Yes, Resend',
        'bulk_heading' => 'Resend Failed WhatsApp Messages',
        'bulk_description' => 'Are you sure you want to resend all selected failed messages?',
        'bulk_submit' => 'Yes, Resend All',
    ],

    // Notifications
    'notifications' => [
        'created' => 'Message created and sent successfully.',
        'resend_success' => [
            'title' => 'Message Resent Successfully',
            'body' => 'Message successfully sent to :phone',
        ],
        'resend_failed' => [
            'title' => 'Failed to Resend Message',
            'body' => ':message',
        ],
        'bulk_resend_all_success' => [
            'title' => 'All Messages Resent Successfully',
            'body' => ':count message(s) sent successfully',
        ],
        'bulk_resend_partial' => [
            'title' => 'Partial Success',
            'body' => ':success message(s) sent successfully, :failed failed',
        ],
        'bulk_resend_all_failed' => [
            'title' => 'All Messages Failed',
            'body' => 'Failed to send :count message(s)',
        ],
    ],

    // Bulk Actions
    'bulk_actions' => [
        'delete' => 'Delete Selected',
        'resend' => 'Resend Selected',
    ],

    // Navigation Badge
    'badge' => [
        'sent_count' => ':count sent',
    ],

    // Tooltips
    'tooltips' => [
        'click_to_copy' => 'Click to copy',
    ],
];
