<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @property-read Permission|null $permission
 * @property-read Role|null $role
 * @property-read Model|Eloquent $setting
 * @property-read User|null $user
 *
 * @method static Builder<static>|SettingAccess newModelQuery()
 * @method static Builder<static>|SettingAccess newQuery()
 * @method static Builder<static>|SettingAccess query()
 *
 * @mixin Eloquent
 */
class SettingAccess extends Model
{
    protected $fillable = [
        'setting_id',
        'role_id',
        'permission_id',
        'user_id',
    ];

    public function setting(): MorphTo
    {
        return $this->morphTo();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
