<?php

namespace Modules\Redirects\Models;

use App\Traits\SpotlightSearchable;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Redirect extends Model
{
    use LogsActivity, SpotlightSearchable;

    protected $fillable = [
        'created_by',
        'from',
        'to',
        'status_code',
        'active',
        'include_query_string',
        'internal',
        'hits',
        'last_accessed_at',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function access()
    {
        return $this->hasMany(RedirectAccess::class);
    }

    public function displayName()
    {
        return $this->from . ' => ' . $this->to;
    }

    public function spotlightTitle()
    {
        return $this->displayName();
    }

    public function spotlightUrl()
    {
        return route('redirects.update', ['redirectId' => $this->id]);
    }

    public function spotlightIcon()
    {
        return 'icon-link-external';
    }

    public function spotlightPermissions(): ?array
    {
        return ['redirects.update'];
    }

    public function spotlightModuleName()
    {
        return 'redirects::spotlight.module_name';
    }

    public function spotlightSearchableFields(): array
    {
        return ['from', 'to'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(function ($eventName) {
                return 'redirects.' . $eventName;
            });
    }
}
