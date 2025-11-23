<?php

return [
    'navigation' => 'Applicant Types',
    'model_label' => 'Applicant Type',
    'plural_model_label' => 'Applicant Types',

    'fields' => [
        'name' => 'Name',
        'code' => 'Code',
    ],

    'columns' => [
        'name' => 'Name',
        'code' => 'Code',
        'usage_count' => 'Usage Count',
        'created_at' => 'Created At',
    ],

    'placeholders' => [
        'name' => 'Example: Seller, Buyer, Witness',
        'code' => 'e.g. seller',
    ],

    'helpers' => [
        'code' => 'Unique code for determining role in document',
    ],
];
