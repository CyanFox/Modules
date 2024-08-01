<?php

return [
    'domains' => [
        'api' => env('TELEMETRYMODULE_DOMAINS_API'),
        'dashboard' => env('TELEMETRYMODULE_DOMAINS_DASHBOARD'),
    ],
    'use_auth' => env('TELEMETRYMODULE_USE_AUTH', false),
];
