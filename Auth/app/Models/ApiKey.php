<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApiKey extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'key',
        'last_used',
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

    public function can($permission)
    {
        return ApiKeyPermission::where('api_key_id', $this->id)
            ->whereHas('permission', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logExcept($this->hidden)
            ->setDescriptionForEvent(function ($eventName) {
                return 'auth.user.api_keys.'.$eventName;
            });
    }
}
