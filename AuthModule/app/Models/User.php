<?php

namespace Modules\AuthModule\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\AuthModule\Database\Factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'theme',
        'language',
        'two_factor_enabled',
        'two_factor_secret',
        'force_change_password',
        'force_activate_two_factor',
        'disabled',
        'password_reset_token',
        'password_reset_expiration',
        'custom_avatar_url',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'remember_token',
        'password_reset_token',
        'password_reset_expiration',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function guardName()
    {
        return config('auth.defaults.guard');
    }
}
