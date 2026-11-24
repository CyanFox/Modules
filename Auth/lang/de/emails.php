<?php

return [
    'forgot_password' => [
        'subject' => 'Passwort zurücksetzen',
        'content' => 'Hallo :name,
<br>Du hast ein Passwort reset für :username angefordert.
<br>Bitte klicke auf den folgenden Link, um dein Passwort zurückzusetzen:
<br><a href=":url">:url</a>
<br>Dieser Link läuft am :expiration ab.
<br>Wenn du kein Link angefordert hast, ist keine weitere Aktion erforderlich.',
    ],
];
