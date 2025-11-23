<?php

return [
    'navigation_label' => 'SPPT',
    'model_label' => 'SPPT',
    'plural_model_label' => 'SPPTs',

    'sections' => [
        'basic_information' => 'Basic Information',
        'area_information' => 'Area Information',
    ],

    'fields' => [
        'number' => 'SPPT Number',
        'year' => 'Year',
        'owner' => 'Owner',
        'block' => 'Block',
        'village' => 'Village',
        'land_area' => 'Land Area',
        'building_area' => 'Building Area',
        'references_count' => 'References',
        'created_at' => 'Created At',
    ],

    'placeholders' => [
        'number' => 'Enter SPPT number',
        'year' => 'Enter SPPT year',
        'owner' => 'Enter owner name',
        'block' => 'Enter block',
        'village' => 'Select village',
        'land_area' => 'Enter land area',
        'building_area' => 'Enter building area',
    ],

    'helpers' => [
        'number' => 'Unique SPPT number for property tax',
    ],

    'filters' => [
        'year' => 'Year',
        'village' => 'Village',
    ],
];
