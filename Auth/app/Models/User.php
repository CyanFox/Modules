<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Auth\Database\Factories\UserFactory;
use Modules\Auth\Traits\WithSession;
use Modules\Auth\Traits\WithTwoFactorAuth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;
use Spatie\LaravelPasskeys\Models\Passkey;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $username
 * @property string $email
 * @property string|null $password
 * @property string $theme
 * @property string $language
 * @property string|null $custom_avatar_url
 * @property string|null $oauth_id
 * @property string|null $password_reset_token
 * @property Carbon|null $password_reset_expiration
 * @property bool $two_factor_enabled
 * @property string|null $two_factor_secret
 * @property bool $force_change_password
 * @property bool $force_activate_two_factor
 * @property bool $disabled
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|UserRecoveryCode[] $recoveryCodes
 * @property-read Collection|Session[] $sessions
 * @property-read Collection|Role[] $roles
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Passkey[] $passkeys
 * @property-read string $fullName
 * @property-read string $avatar
 */
class User extends Authenticatable implements HasPasskeys
{
    use HasFactory, HasRoles, InteractsWithPasskeys, LogsActivity, Notifiable, WithSession, WithTwoFactorAuth;

    protected $guarded = [];

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
        return $this->first_name.' '.$this->last_name;
    }

    public function avatar()
    {
        if ($this->custom_avatar_url) {
            return e($this->custom_avatar_url);
        }

        $filePath = 'avatars/'.$this->id.'.png';
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

    public function getPasskeyDisplayName(): string
    {
        return $this->username;
    }

    public function displayName()
    {
        if ($this->first_name || $this->last_name) {
            return $this->fullName();
        }

        return $this->username;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logExcept($this->hidden)
            ->setDescriptionForEvent(function ($eventName) {
                $changes = $this->getChanges();
                unset($changes['updated_at']);

                if (array_keys($changes) === ['password']) {
                    return 'auth.user.password_'.$eventName;
                }

                if (array_keys($changes) === ['password', 'password_reset_token', 'password_reset_expiration']) {
                    return 'auth.user.forgot_password.reset';
                }

                return 'auth.user_'.$eventName;
            });
    }

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }
}
