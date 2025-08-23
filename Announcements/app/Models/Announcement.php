<?php

namespace Modules\Announcements\Models;

use App\Traits\SpotlightSearchable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
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
    use LogsActivity, SpotlightSearchable;

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

    public function spotlightTitle()
    {
        return $this->displayName();
    }

    public function spotlightDescription(): string
    {
        return Str::limit($this->description ?? '', 20, preserveWords: true);
    }

    public function spotlightUrl()
    {
        return route('admin.announcements.update', $this->id);
    }

    public function spotlightIcon()
    {
        return $this->icon ? 'icon-'.$this->icon : 'icon-megaphone';
    }

    public function spotlightPermissions(): ?array
    {
        return ['admin.announcements.update'];
    }

    public function spotlightModuleName()
    {
        return 'announcements::spotlight.module_name';
    }

    public function spotlightSearchableFields(): array
    {
        return ['title', 'description'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(function ($eventName) {
                return 'announcement_'.$eventName;
            });
    }
}
