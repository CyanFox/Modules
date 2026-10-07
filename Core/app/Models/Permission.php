<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Permission extends \Spatie\Permission\Models\Permission
{
    use LogsActivity;

    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logExcept($this->hidden)
            ->logOnlyDirty()
            ->setDescriptionForEvent(function ($eventName) {
                $changes = $this->getChanges();
                unset($changes['updated_at']);

                return 'permissions.' . $eventName;
            });
    }
}
