<?php

namespace Modules\Announcements\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\User;

class DismissedAnnouncement extends Model
{
    protected $fillable = [
        'announcement_id',
        'user_id',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
