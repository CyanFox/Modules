<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $user_id
 * @property string $key
 * @property Carbon|null $last_used
 * @property-read User $user
 * @property-read Collection|ApiKeyPermission[] $permissions
 */
class ApiKey extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'key',
        'last_used',
        'connected_device'
    ];

    protected $hidden = [
        'key',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(ApiKeyPermission::class);
    }

    public function hasPermission($permission)
    {
        if ($this->connected_device && $this->user->can($permission)) {
            return true;
        }

        if (!$this->user->can($permission) || !$this->can($permission)) {
            return false;
        }
        return true;
    }

    public function can($permission)
    {
        if ($this->connected_device) {
            return true;
        }

        return ApiKeyPermission::where('api_key_id', $this->id)
            ->whereHas('permission', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function sendNoPermissionResponse()
    {
        return apiResponse('Unauthorized', null, false, 403);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logExcept($this->hidden)
            ->setDescriptionForEvent(function ($eventName) {
                return 'auth.user.api_keys.' . $eventName;
            });
    }

    public function displayName()
    {
        return $this->key;
    }
}
