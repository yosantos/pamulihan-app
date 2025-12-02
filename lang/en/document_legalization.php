<?php

return [
    'navigation_label' => 'Document Legalizations',
    'plural_label' => 'Document Legalizations',
    'model_label' => 'Document Legalization',

    'sections' => [
        'legalization_info' => 'Legalization Information',
        'applicant_info' => 'Applicant Information',
    ],

    'fields' => [
        'number_legalization' => 'Legalization Number',
        'date' => 'Date',
        'type_of_document' => 'Type of Document',
        'name' => 'Applicant Name',
        'occupation' => 'Occupation',
        'address' => 'Address',
        'village' => 'Village',
        'main_content_of_document' => 'Main Content of Document',
        'note' => 'Note',
        'created_at' => 'Created At',
    ],

    'placeholders' => [
        'auto_generated' => 'Auto-generated',
    ],

    'filters' => [
        'date_from' => 'Date From',
        'date_until' => 'Date Until',
    ],
];
