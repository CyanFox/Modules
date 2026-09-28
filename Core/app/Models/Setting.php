<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

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
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'properties',
    ];

    public function access(): MorphMany
    {
        return $this->morphMany(SettingAccess::class, 'setting');
    }

    public function hasProperty(string $property): bool
    {
        return isset($this->properties[$property]);
    }

    public function getProperty(string $property, mixed $default = null): mixed
    {
        return $this->properties[$property] ?? $default;
    }

    // TODO: Activity Log
}
