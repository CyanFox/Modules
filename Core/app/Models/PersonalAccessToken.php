<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use LogsActivity;

    public function getDisplayName(): string
    {
        $properties = json_decode($this->name);

        return data_get($properties, 'name') ?? '';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logExcept($this->hidden)
            ->setDescriptionForEvent(function ($eventName) {
                $changes = $this->getChanges();
                unset($changes['updated_at']);

                return 'user.api_key.' . $eventName;
            });
    }
}
