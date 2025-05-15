<?php

return [
    'photo' => 'Foto',

    'change_password' => [
        'tab_title' => 'Passwort ändern',

        'current_password' => 'Aktuelles Passwort',
        'new_password' => 'Neues Passwort',
        'confirm_password' => 'Passwort bestätigen',
        'password_same' => 'Die Passwörter stimmen nicht überein.',
        'old_password_used' => 'Das alte Passwort kann nicht als neues Passwort verwendet werden.',

        'buttons' => [
            'change_password' => 'Passwort ändern',
        ],

        'notifications' => [
            'password_changed' => 'Passwort erfolgreich geändert.',
        ],
    ],

    'activate_two_factor' => [
        'tab_title' => '2-Faktor aktivieren',

        'current_password' => 'Aktuelles Passwort',
        'two_fa_code' => '2-Faktor Code',
        'invalid_two_factor_code' => 'Dieser 2-Faktor-Code ist ungültig.',
        'recovery_codes' => 'Speichere diese Wiederherstellungscodes an einem sicheren Ort. Sie können danach nicht mehr angezeigt werden.',

        'buttons' => [
            'activate_two_fa' => '2-Faktor aktivieren',
            'download_recovery_codes' => 'Herunterladen',
            'finish' => 'Fertig',
        ],

        'notifications' => [
            'two_fa_enabled' => '2-Faktor-Authentifizierung erfolgreich aktiviert.',
        ],
    ]
];
