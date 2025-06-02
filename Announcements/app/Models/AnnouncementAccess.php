<?php

namespace Modules\Announcements\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\Auth\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @property int $id
 * @property int $announcement_id
 * @property int|null $group_id
 * @property int|null $permission_id
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Announcement $announcement
 * @property-read Role|null $group
 * @property-read Permission|null $permission
 * @property-read User|null $user
 */
class AnnouncementAccess extends Model
{
    protected $table = 'announcement_access';

    protected $fillable = [
        'announcement_id',
        'group_id',
        'permission_id',
        'user_id',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function group()
    {
        return $this->belongsTo(Role::class, 'group_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
