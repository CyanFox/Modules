<?php

declare(strict_types=1);

namespace Modules\Core\Enums;

class SettingsProperty
{
    const LANG_KEY = 'lang_key'; // The language key of the setting for the settings page
    const TYPE = 'type'; // The data type of the setting (string, int, bool etc.)
    const MODULE = 'module'; // The module the setting belongs to
    const ENCRYPTED = 'encrypted'; // Whether the value is encrypted
    const INTERNAL = 'internal'; // Can't be accessed by the api (api keys etc.)
    const AUTH = 'auth'; // Only visible to authenticated users
    const HIDDEN = 'hidden'; // Hides the setting from the settings page
}
