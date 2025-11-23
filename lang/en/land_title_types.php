<?php

return [
    'navigation' => 'Land Title Types',
    'model_label' => 'Land Title Type',
    'plural_model_label' => 'Land Title Types',

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
        'name' => 'Example: Sale and Purchase, Grant, Inheritance',
        'code' => 'e.g. sale_purchase',
    ],

    'helpers' => [
        'code' => 'Unique code for determining document template',
    ],
];
