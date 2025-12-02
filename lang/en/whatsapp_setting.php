<?php

return [
    'navigation_label' => 'WhatsApp Setting',
    'title' => 'WhatsApp Connection Settings',

    'actions' => [
        'refresh' => 'Refresh Status',
        'logout' => 'Logout',
    ],

    'qr_code' => [
        'title' => 'Scan QR Code to Login',
        'description' => 'Scan this QR code with your WhatsApp mobile app to connect',
        'auto_refresh' => 'Auto-refreshing every 10 seconds...',
        'not_available' => 'QR Code not available. Please refresh the page.',
    ],

    'instructions' => [
        'title' => 'How to Connect WhatsApp',
        'step_1' => 'Open WhatsApp on your phone',
        'step_2' => 'Tap Menu (⋮) or Settings and select Linked Devices',
        'step_3' => 'Tap Link a Device',
        'step_4' => 'Point your phone at this screen to scan the QR code',
    ],

    'status' => [
        'connected' => 'Connected',
        'connected_description' => 'Your WhatsApp account is successfully connected and ready to send messages.',
        'unknown' => 'Unable to determine WhatsApp connection status. Please try refreshing the page.',
    ],

    'modals' => [
        'logout' => [
            'heading' => 'Logout from WhatsApp',
            'description' => 'Are you sure you want to disconnect this WhatsApp account? You will need to scan the QR code again to reconnect.',
            'submit' => 'Logout',
        ],
    ],

    'notifications' => [
        'status_check_failed' => 'Failed to check WhatsApp status',
        'connection_error' => 'Connection Error',
        'logout_success' => 'Successfully logged out from WhatsApp',
        'logout_failed' => 'Failed to logout',
        'logout_error' => 'Logout Error',
    ],
];
