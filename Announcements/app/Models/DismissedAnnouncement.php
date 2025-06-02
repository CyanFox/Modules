<?php

namespace Modules\Announcements\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\Auth\Models\User;

/**
 * @property int $id
 * @property int $announcement_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Announcement $announcement
 * @property-read User $user
 */
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
