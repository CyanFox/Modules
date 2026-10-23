<?php

declare(strict_types=1);

namespace Modules\Core\Enums;

class SettingsProperty
{
    const LANG_KEY = 'lang_key'; // The language key of the setting for the settings page
    const TYPE = 'type'; // The data type of the setting (string, int, bool etc.)
    const RULES = 'rules'; // Validation rules for the setting. Used when saving settings via the api / settings page
    const MODULE = 'module'; // The module the setting belongs to
    const ENCRYPTED = 'encrypted'; // Whether the value is encrypted
    const INTERNAL = 'internal'; // Can't be accessed by the api (for. api keys, secrets etc.)
    const AUTH = 'auth'; // Only visible to authenticated users
    const HIDDEN = 'hidden'; // Hides the setting from the user settings page
    const PERMISSIONS = 'permissions'; // Permissions required to view the setting
    const ROLES = 'roles'; // Roles required to view the setting
}
