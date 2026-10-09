<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\LaravelPasskeys\Database\Factories\PasskeyFactory;
use Spatie\LaravelPasskeys\Models\Passkey as BasePasskey;
use Webauthn\PublicKeyCredentialSource;

/**
 * @property int $id
 * @property int $authenticatable_id
 * @property string $name
 * @property string $credential_id
 * @property PublicKeyCredentialSource $data
 * @property Carbon|null $last_used_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read User $authenticatable
 * @method static PasskeyFactory factory($count = null, $state = [])
 * @method static Builder<static>|Passkey newModelQuery()
 * @method static Builder<static>|Passkey newQuery()
 * @method static Builder<static>|Passkey query()
 * @method static Builder<static>|Passkey whereAuthenticatableId($value)
 * @method static Builder<static>|Passkey whereCreatedAt($value)
 * @method static Builder<static>|Passkey whereCredentialId($value)
 * @method static Builder<static>|Passkey whereData($value)
 * @method static Builder<static>|Passkey whereId($value)
 * @method static Builder<static>|Passkey whereLastUsedAt($value)
 * @method static Builder<static>|Passkey whereName($value)
 * @method static Builder<static>|Passkey whereUpdatedAt($value)
 * @mixin Eloquent
 * @mixin IdeHelperPasskey
 */
class Passkey extends BasePassKey
{
    use LogsActivity;

    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'last_used_at'])
            ->setDescriptionForEvent(function ($eventName) {
                $changes = $this->getChanges();
                unset($changes['updated_at']);

                return 'user.passkeys.' . $eventName;
            });
    }
}
