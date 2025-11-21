<?php

return [

    'custom_template' => true,

    'prefix_path' => env('ANTI_CRUD_PREFIX', 'Anti'),

    'path' => base_path('resources/stubs/'),

    'view_columns_number' => 5,

    'custom_delimiter' => ['%%', '%%'],

    'route_api_file' => 'routes/anti-api.php',

    'route_web_file' => 'routes/anti-web.php',
];
