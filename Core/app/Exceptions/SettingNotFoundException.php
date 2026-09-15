<?php

declare(strict_types=1);

namespace Modules\Core\Exceptions;

use Exception;

class SettingNotFoundException extends Exception
{
    public string $key;

    public function __construct($key)
    {
        $this->key = $key;
        parent::__construct("Setting with key {$key} not found");
    }
}
