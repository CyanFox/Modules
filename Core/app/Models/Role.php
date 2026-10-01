<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Role extends \Spatie\Permission\Models\Role
{
    use LogsActivity;

    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logExcept($this->hidden)
            ->logOnlyDirty()
            ->setDescriptionForEvent(function ($eventName) {
                $changes = $this->getChanges();
                unset($changes['updated_at']);

                return 'roles.' . $eventName;
            });
    }
}
