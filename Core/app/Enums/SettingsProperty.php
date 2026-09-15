<?php

declare(strict_types=1);

namespace Modules\Core\Enums;

enum SettingsProperty: string
{
    case LANG_KEY = 'lang_key';
    case TYPE = 'type';
    case MODULE = 'module';
    case ENCRYPTED = 'encrypted';
    case INTERNAL = 'internal';
}
