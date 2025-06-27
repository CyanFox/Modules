<?php

namespace Modules\Announcements\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $icon
 * @property string $color
 * @property bool $dismissible
 * @property bool $disabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|AnnouncementAccess[] $access
 * @property-read Collection|DismissedAnnouncement[] $dismissed
 */
class Announcement extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'icon',
        'color',
        'description',
        'dismissible',
        'disabled',
    ];

    public function access(): HasMany
    {
        return $this->hasMany(AnnouncementAccess::class);
    }

    public function dismissed(): HasMany
    {
        return $this->hasMany(DismissedAnnouncement::class);
    }

    public function displayName()
    {
        return $this->title;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->setDescriptionForEvent(function ($eventName) {
                return 'announcement_'.$eventName;
            });
    }
}
