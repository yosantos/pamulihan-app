<?php

return [
    // Navigation & Model
    'navigation' => 'WhatsApp Campaigns',
    'navigation_group' => 'Communication',
    'model_label' => 'WhatsApp Campaign',
    'plural_model_label' => 'WhatsApp Campaigns',

    // Pages
    'pages' => [
        'list' => 'List WhatsApp Campaigns',
        'create' => 'Create WhatsApp Campaign',
        'edit' => 'Edit WhatsApp Campaign',
        'view' => 'View WhatsApp Campaign',
    ],

    // Sections
    'sections' => [
        'campaign_information' => [
            'title' => 'Campaign Information',
        ],
        'message_template' => [
            'title' => 'Message Template',
        ],
        'dynamic_variables' => [
            'title' => 'Dynamic Variables',
            'description' => 'Define the dynamic variables that need to be provided when sending messages using this campaign',
        ],
    ],

    // Fields
    'fields' => [
        'name' => 'Campaign Name',
        'company_name' => 'Company Name',
        'description' => 'Description',
        'is_active' => 'Active',
        'template' => 'Template',
        'variables' => 'Required Variables',
        'usage_count' => 'Usage',
        'creator' => 'Created By',
        'created_at' => 'Created At',
    ],

    // Field Helpers
    'helpers' => [
        'name' => 'A descriptive name for this campaign',
        'company_name' => 'This will replace [Name_Company] in the template',
        'is_active' => 'Inactive campaigns cannot be used to send messages',
        'template' => 'Use [variable_name] for placeholders. [Name_Company] will be automatically replaced with the company name above.',
        'variables' => 'These are the dynamic variables that must be provided when sending messages. Name_Company is automatically included.',
    ],

    // Placeholders
    'placeholders' => [
        'name' => 'e.g., OTP Campaign',
        'company_name' => 'e.g., Pamulihan App',
        'description' => 'Brief description of this campaign purpose',
        'template' => '[Name_Company]. Hello, your OTP is [code]. Please use it within 5 minutes.',
        'variables' => 'Add variable name (e.g., code, name, amount)',
    ],

    // Template Info
    'template' => [
        'character_count' => ':count characters',
        'detected_variables' => 'Detected Variables',
        'no_variables' => 'No variables detected',
        'static_label' => 'static',
        'dynamic_label' => 'dynamic',
    ],

    // Table Columns
    'columns' => [
        'name' => 'Campaign Name',
        'company' => 'Company',
        'template_preview' => 'Template Preview',
        'variables' => 'Variables',
        'active' => 'Active',
        'usage' => 'Usage',
        'created_by' => 'Created By',
        'created_at' => 'Created At',
    ],

    // Filters
    'filters' => [
        'status' => 'Status',
        'all_campaigns' => 'All campaigns',
        'active_campaigns' => 'Active campaigns',
        'inactive_campaigns' => 'Inactive campaigns',
        'has_usage' => 'Has been used',
    ],

    // Actions
    'actions' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'preview' => 'Preview',
        'duplicate' => 'Duplicate',
        'send_message' => 'Send Message',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate',
    ],

    // Preview Modal
    'preview' => [
        'heading' => 'Campaign Preview',
        'close' => 'Close',
    ],

    // Modal Confirmations
    'modals' => [
        'delete' => [
            'heading' => 'Delete Campaign',
            'description' => 'Are you sure you want to delete this campaign? Messages sent using this campaign will remain but will lose the campaign reference.',
        ],
        'duplicate' => [
            'heading' => 'Duplicate Campaign',
            'description' => 'Are you sure you want to duplicate this campaign?',
        ],
        'activate' => [
            'heading' => 'Activate Campaigns',
            'description' => 'Are you sure you want to activate the selected campaigns?',
        ],
        'deactivate' => [
            'heading' => 'Deactivate Campaigns',
            'description' => 'Are you sure you want to deactivate the selected campaigns?',
        ],
    ],

    // Notifications
    'notifications' => [
        'created' => 'Campaign created successfully.',
        'updated' => 'Campaign updated successfully.',
        'deleted' => 'Campaign deleted successfully.',
        'duplicated' => [
            'title' => 'Campaign duplicated',
            'body' => 'Campaign \':name\' has been duplicated successfully.',
        ],
        'activated' => [
            'title' => 'Campaigns activated',
            'body' => ':count campaign(s) have been activated.',
        ],
        'deactivated' => [
            'title' => 'Campaigns deactivated',
            'body' => ':count campaign(s) have been deactivated.',
        ],
    ],

    // Bulk Actions
    'bulk_actions' => [
        'delete' => 'Delete Selected',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate',
    ],
];
