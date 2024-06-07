<?php

namespace Modules\AuthModule\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\AuthModule\Models\User;
use Modules\AuthModule\Services\Users\Auth\AuthUserService;
use Modules\AuthModule\Services\Users\UserService;

/**
 * @method static User findUser(int $userId)
 * @method static User findUserByUsername(string $username)
 * @method static User findUserByEmail(string $email)
 * @method static AuthUserService getUser(User $user)
 */
class UserManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return UserService::class;
    }
}
