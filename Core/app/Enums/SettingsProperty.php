<?php

declare(strict_types=1);

namespace Modules\Core\Enums;

enum SettingsProperty: string
{
    case LANG_KEY = 'lang_key'; // The language key of the setting for the settings page
    case TYPE = 'type'; // The data type of the setting (string, int, bool etc.)
    case MODULE = 'module'; // The module the setting belongs to
    case ENCRYPTED = 'encrypted'; // Whether the value is encrypted
    case INTERNAL = 'internal'; // Can't be accessed by the api (api keys etc.)
    case AUTH = 'auth'; // Only visible to authenticated users
    case HIDDEN = 'hidden'; // Hides the setting from the settings page
}
