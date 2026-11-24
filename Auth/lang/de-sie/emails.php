<?php

return [
    'forgot_password' => [
        'subject' => 'Passwort zurücksetzen',
        'content' => 'Hallo :name,
<br>Sie haben ein Passwort reset für :username angefordert.
<br>Bitte klicken Sie folgenden Link, um Ihr Passwort zurückzusetzen:
<br><a href=":url">:url</a>
<br>Dieser Link läuft am :expiration ab.
<br>Wenn Sie kein Link angefordert haben, ist keine weitere Aktion erforderlich.',
    ],
];
