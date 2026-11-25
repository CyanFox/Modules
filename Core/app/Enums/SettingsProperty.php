<?php

declare(strict_types=1);

namespace Modules\Core\Enums;

class SettingsProperty
{
    const string LANG_KEY = 'lang_key'; // The language key of the setting for the settings page

    const string TYPE = 'type'; // The data type of the setting (string, int, bool etc.)

    const string RULES = 'rules'; // Validation rules for the setting. Used when saving settings via the api / settings page

    const string MODULE = 'module'; // The module the setting belongs to

    const string ENCRYPTED = 'encrypted'; // Whether the value is encrypted

    const string INTERNAL = 'internal'; // Can't be accessed by the api (for. api keys, secrets etc.)

    const string AUTH = 'auth'; // Only visible to authenticated users

    const string HIDDEN = 'hidden'; // Hides the setting from the user settings page

    const string PERMISSIONS = 'permissions'; // Permissions required to view the setting

    const string ROLES = 'roles'; // Roles required to view the setting
}
