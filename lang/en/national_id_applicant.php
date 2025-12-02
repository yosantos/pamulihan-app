<?php

return [
    'navigation_label' => 'National ID Applicants',
    'plural_label' => 'National ID Applicants',
    'model_label' => 'National ID Applicant',

    'sections' => [
        'applicant_information' => 'Applicant Information',
    ],

    'fields' => [
        'no_register' => 'Registration Number',
        'date' => 'Registration Date',
        'national_id_number' => 'National ID Number',
        'name' => 'Full Name',
        'address' => 'Address',
        'village' => 'Village',
        'sex' => 'Gender',
        'created_at' => 'Created At',
    ],

    'placeholders' => [
        'auto_generated' => 'Auto-generated',
    ],

    'sex_options' => [
        'female' => 'Female',
        'male' => 'Male',
    ],

    'filters' => [
        'date_from' => 'Date From',
        'date_until' => 'Date Until',
    ],
];
