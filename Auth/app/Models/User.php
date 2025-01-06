<?php

namespace Modules\Auth\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Modules\Auth\Traits\WithSession;
use Modules\Auth\Traits\WithTwoFactorAuth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable, WithTwoFactorAuth, WithSession;

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'theme',
        'language',
        'custom_avatar_url',
        'two_factor_enabled',
        'two_factor_secret',
        'force_change_password',
        'force_activate_two_factor',
        'password_reset_token',
        'password_reset_expiration',
        'disabled',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'remember_token',
        'password_reset_token',
        'password_reset_expiration',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'force_change_password' => 'boolean',
        'force_activate_two_factor' => 'boolean',
        'disabled' => 'boolean',
    ];

    public function recoveryCodes()
    {
        return $this->hasMany(UserRecoveryCode::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function avatar()
    {
        if ($this->custom_avatar_url) {
            return $this->custom_avatar_url;
        }

        $filePath = 'avatars/' . $this->id . '.png';
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->url($filePath);
        }

        $placeholders = [
            '{email}' => $this->email,
            '{email_md5}' => md5($this->email),
            '{username}' => $this->username,
            '{first_name}' => $this->first_name,
            '{last_name}' => $this->last_name,
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), settings('auth.default_avatar_url'));
    }

    public function guardName()
    {
        return config('auth.defaults.guard');
    }
}
