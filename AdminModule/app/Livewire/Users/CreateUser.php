<?php

namespace Modules\AdminModule\Livewire\Users;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use Modules\AuthModule\Rules\Password;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateUser extends LWComponent
{
    public $firstName;

    public $lastName;

    public $username;

    public $email;

    public $password;

    public $passwordConfirmation;

    public $groups;

    public $permissions;

    public $forceActivateTwoFactor;

    public $forceChangePassword;

    public $disabled;

    public $groupList;

    public $permissionList;

    public $passwordRules;

    public function createUser()
    {
        if (Auth::user()->cannot('adminmodule.users.create')) {
            return;
        }
        $this->validate([
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'max:255', 'same:passwordConfirmation', new Password],
            'passwordConfirmation' => 'required|max:255|same:password',
            'groups' => 'nullable|array',
            'permissions' => 'nullable|array',
            'forceChangePassword' => 'nullable|boolean',
            'forceActivateTwoFactor' => 'nullable|boolean',
            'disabled' => 'nullable|boolean',
        ]);

        try {
            $user = User::create([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'force_change_password' => (bool) $this->forceChangePassword,
                'force_activate_two_factor' => (bool) $this->forceActivateTwoFactor,
                'disabled' => (bool) $this->disabled,
            ]);

            $user->syncRoles($this->groups);
            $user->syncPermissions($this->permissions);
            UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('adminmodule::users.create_user.notifications.user_created'))
            ->success()
            ->send();

        $this->redirect(route('admin.users'), navigate: true);
    }

    public function mount()
    {
        $this->groupList = Role::all()->map(function ($role) {
            return [
                'label' => $role->name,
                'value' => $role->name,
            ];
        })->toArray();

        $this->permissionList = Permission::all()->map(function ($permission) {
            return [
                'label' => $permission->name,
                'value' => $permission->name,
            ];
        })->toArray();

        if (setting('authmodule.password.minimum_length')) {
            $this->passwordRules = [
                'min:' . setting('authmodule.password.minimum_length'),
            ];
        }

        if (setting('authmodule.password.require.numbers')) {
            $this->passwordRules[] = 'regex:/[0-9]/';
        }

        if (setting('authmodule.password.require.special_characters')) {
            $this->passwordRules[] = 'regex:/[^a-zA-Z0-9]/';
        }

        if (setting('authmodule.password.require.uppercase')) {
            $this->passwordRules[] = 'regex:/[A-Z]/';
        }

        if (setting('authmodule.password.require.lowercase')) {
            $this->passwordRules[] = 'regex:/[a-z]/';
        }

        if (setting('authmodule.password.blacklist')) {
            $this->passwordRules[] = 'not_in:' . setting('authmodule.password.blacklist');
        }

    }

    public function render()
    {
        return $this->renderView('adminmodule::livewire.users.create-user', __('adminmodule::users.create_user.tab_title'), 'adminmodule::components.layouts.app');
    }
}
