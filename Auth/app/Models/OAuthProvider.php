<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property int $id
 * @property string $name
 * @property string|null $color
 * @property string|null $icon
 * @property string $discovery_url
 * @property string $client_id
 * @property string $client_secret
 * @property bool $account_linking
 * @property array<array-key, mixed> $fields
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class OAuthProvider extends Model
{
    use LogsActivity;

    protected $table = 'oauth_providers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'color',
        'icon',
        'discovery_url',
        'client_id',
        'client_secret',
        'account_linking',
        'fields',
    ];

    protected $casts = [
        'account_linking' => 'boolean',
        'fields' => 'array',
        'client_secret' => 'encrypted',
    ];

    protected $hidden = [
        'client_secret',
    ];

    public function getDisplayName()
    {
        return $this->name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept($this->hidden)
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function ($eventName) {
                return 'oauth_providers.'.$eventName;
            });
    }
}
