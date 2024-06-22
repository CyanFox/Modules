<?php

return [
    'routes' => [
        'dashboard' => true,
        'home' => true,
    ],

    'domains' => [
        'dashboard' => env('DASHBOARDMODULE_DOMAINS_DASHBOARD'),
        'home' => env('DASHBOARDMODULE_DOMAINS_HOME'),
    ],
];
