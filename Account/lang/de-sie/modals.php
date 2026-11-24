<?php

return [
    'activity_details' => [
        'title' => 'Aktivitätsdetails',

        'old_values' => 'Alte Werte',
        'new_values' => 'Neue Werte',
    ],

    'show_login_qr_code' => [
        'title' => 'Login QR-Code',
        'description' => 'Scannen Sie den QR-Code mit der App um Sie anzumelden. Der QR-Code läuft in 5 Minuten ab',
    ],

    'create_api_key' => [
        'title' => 'API-Key erstellen',
        'token_description' => 'Speichern Sie diesen Token in einem sicheren Ort. Sie werden ihn nicht wieder sehen können.',

        'name' => 'Name',
        'permissions' => 'Berechtigungen',

        'buttons' => [
            'create' => 'API-Key erstellen',
        ],

        'notifications' => [
            'created' => 'API Key erfolgreich erstellt.',
        ],
    ],

    'enable_mfa' => [
        'title' => 'MFA Aktivieren',
        'code' => 'Code',

        'invalid_code' => 'Der angegebene Code ist ungültig',

        'recovery_codes' => 'Wiederherstellungscodes',
        'recovery_codes_description' => 'Speichern Sie diese Wiederherstellungscodes in einem sicheren Ort. Sie werden sie nicht wieder sehen können.',

        'buttons' => [
            'enable' => 'Aktivieren',
            'regenerate' => 'Wiederherstellungscodes neu generieren',
            'download' => 'Wiederherstellungscodes herunterladen',
        ],

        'notifications' => [
            'enabled' => 'MFA erfolgreich aktiviert.',
        ],
    ],

    'regenerate_recovery_codes' => [
        'title' => 'Wiederherstellungscodes',
        'description' => 'Speichern Sie diese Wiederherstellungscodes in einem sicheren Ort. Sie werden sie nicht wieder sehen können.',

        'buttons' => [
            'regenerate' => 'Wiederherstellungscodes neu generieren',
            'download' => 'Wiederherstellungscodes herunterladen',
        ],
    ],

    'change_avatar' => [
        'title' => 'Avatar ändern',

        'avatar' => 'Avatar',
        'custom_avatar_url' => 'Eigene Avatar Url',

        'buttons' => [
            'change' => 'Avatar ändern',
            'reset' => 'Avatar zurücksetzen',
        ],

        'notifications' => [
            'changed' => 'Avatar erfolgreich geändert.',
            'reset' => 'Avatar erfolgreich zurückgesetzt.',
        ],
    ],
];
