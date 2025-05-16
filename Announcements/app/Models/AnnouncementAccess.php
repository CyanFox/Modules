<?php

namespace Modules\Announcements\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
