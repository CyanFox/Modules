<?php

return [
    'minimum_length' => 12,
    'require' => [
        'uppercase' => true,
        'lowercase' => true,
        'numbers' => true,
        'special_characters' => true,
    ],
    'blacklist' => 'password,password1234',
];
