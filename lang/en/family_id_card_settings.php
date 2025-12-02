<?php

return [
    'title' => 'Family ID Card Settings',
    'heading' => 'Family ID Card WhatsApp Notification Settings',

    'navigation' => [
        'label' => 'Family ID Card Settings',
    ],

    'section' => [
        'title' => 'WhatsApp Campaign Configuration',
        'description' => 'Configure WhatsApp campaigns for automatic notifications at different stages of Family ID Card registration.',
    ],

    'fields' => [
        'registration_campaign' => 'Registration Campaign',
        'rejection_campaign' => 'Rejection Campaign',
        'completion_campaign' => 'Completion Campaign',
    ],

    'placeholders' => [
        'select_campaign' => 'Select a campaign',
    ],

    'helpers' => [
        'registration_campaign' => 'This campaign will be sent automatically when a new Family ID Card registration is created.',
        'rejection_campaign' => 'This campaign will be sent automatically when a registration is rejected.',
        'completion_campaign' => 'This campaign will be sent automatically when a registration is marked as completed.',
    ],

    'actions' => [
        'save' => 'Save Settings',
    ],

    'notifications' => [
        'saved' => 'Settings Saved',
        'settings_updated' => 'Family ID Card notification settings have been updated successfully.',
    ],
];
