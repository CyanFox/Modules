<?php

namespace Modules\Announcements\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'icon',
        'color',
        'description',
        'dismissible',
        'disabled',
    ];

    public function access()
    {
        return $this->hasMany(AnnouncementAccess::class);
    }

    public function dismissed()
    {
        return $this->hasMany(DismissedAnnouncement::class);
    }
}
