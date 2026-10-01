<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property int $user_id
 * @property string $key
 * @property string|null $value
 * @property string|null $properties
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static Builder<static>|UserSetting newModelQuery()
 * @method static Builder<static>|UserSetting newQuery()
 * @method static Builder<static>|UserSetting query()
 * @method static Builder<static>|UserSetting whereCreatedAt($value)
 * @method static Builder<static>|UserSetting whereId($value)
 * @method static Builder<static>|UserSetting whereKey($value)
 * @method static Builder<static>|UserSetting whereProperties($value)
 * @method static Builder<static>|UserSetting whereUpdatedAt($value)
 * @method static Builder<static>|UserSetting whereUserId($value)
 * @method static Builder<static>|UserSetting whereValue($value)
 *
 * @mixin Eloquent
 */
class UserSetting extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'key',
        'value',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(userModel());
    }

    public function hasProperty(string $property): bool
    {
        $properties = $this->properties;

        if (is_string($properties)) {
            $properties = json_decode($properties, true) ?? [];
        }

        return isset($properties[$property]);
    }

    public function getProperty(string $property): mixed
    {
        $properties = $this->properties;

        if (is_string($properties)) {
            $properties = json_decode($properties, true) ?? [];
        }

        return $properties[$property] ?? null;
    }

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            Cache::remember("user_settings_$model->id", now()->addDay(), function () use ($model) {
                return $model;
            });
        });

        static::creating(function ($model) {
            Cache::remember("user_settings_$model->id", now()->addDay(), function () use ($model) {
                return $model;
            });

            return true;
        });

        static::updating(function ($model) {
            Cache::forget("user_settings_$model->id");

            return true;
        });

        static::deleting(function ($model) {
            Cache::forget("user_settings_$model->id");

            return true;
        });
    }

    public function getDisplayName(): string
    {
        return $this->key;
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

                return 'user.settings.' . $eventName;
            });
    }
}
