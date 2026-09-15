<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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
    protected $fillable = [
        'user_id',
        'key',
        'value',
        'properties',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasProperty(string $property): bool
    {
        return isset($this->properties[$property]);
    }

    public function getProperty(string $property, mixed $default = null): mixed
    {
        return $this->properties[$property] ?? $default;
    }
}
