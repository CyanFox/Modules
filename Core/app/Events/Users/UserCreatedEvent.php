<?php

declare(strict_types=1);

namespace Modules\Core\Events\Users;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public $user,
    )
    {
    }
}
