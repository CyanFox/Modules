<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string|null $properties
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, SettingAccess> $access
 * @property-read int|null $access_count
 *
 * @method static Builder<static>|Setting newModelQuery()
 * @method static Builder<static>|Setting newQuery()
 * @method static Builder<static>|Setting query()
 * @method static Builder<static>|Setting whereCreatedAt($value)
 * @method static Builder<static>|Setting whereId($value)
 * @method static Builder<static>|Setting whereKey($value)
 * @method static Builder<static>|Setting whereProperties($value)
 * @method static Builder<static>|Setting whereUpdatedAt($value)
 * @method static Builder<static>|Setting whereValue($value)
 *
 * @mixin Eloquent
 */
class Setting extends Model
{
    use LogsActivity;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function access(): MorphMany
    {
        return $this->morphMany(SettingAccess::class, 'setting');
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
            Cache::remember("settings_$model->id", now()->addDay(), function () use ($model) {
                return $model;
            });
        });

        static::creating(function ($model) {
            Cache::remember("settings_$model->id", now()->addDay(), function () use ($model) {
                return $model;
            });

            return true;
        });

        static::updating(function ($model) {
            Cache::forget("settings_$model->id");

            return true;
        });

        static::deleting(function ($model) {
            Cache::forget("settings_$model->id");

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

                return 'settings.' . $eventName;
            });
    }
}
