<?php

namespace Modules\Redirects\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\Permission;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;

class RedirectAccess extends Model
{
    protected $table = 'redirect_access';

    protected $fillable = [
        'redirect_id',
        'user_id',
        'permission_id',
        'role_id',
        'can_update',
    ];

    public function redirect()
    {
        return $this->belongsTo(Redirect::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
