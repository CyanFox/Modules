<?php

namespace Modules\AdminModule\Livewire;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Modules\AuthModule\Models\User;
use Modules\AuthModule\Rules\Password;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Users extends LWComponent
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
    public $userId;

    public function createUser()
    {
        $this->validate([
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'max:255', 'same:passwordConfirmation', new Password()],
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
                'force_change_password' => $this->forceChangePassword,
                'force_activate_two_factor' => $this->forceActivateTwoFactor,
                'disabled' => $this->disabled,
            ]);

            $user->syncRoles($this->groups);
            $user->syncPermissions($this->permissions);
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

    #[On('updateUserParams')]
    public function updateUserParams($userId)
    {
        try {

            $user = User::findOrFail($userId);

            $this->userId = $user->id;

            $this->firstName = $user->first_name;
            $this->lastName = $user->last_name;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->forceChangePassword = $user->force_change_password;
            $this->forceActivateTwoFactor = $user->force_activate_two_factor;
            $this->disabled = $user->disabled;
            $this->groups = $user->roles->pluck('name')->toArray();
            $this->permissions = $user->permissions->pluck('name')->toArray();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }
    }

    public function updateUser()
    {
        $this->validate([
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username,' . $this->userId,
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => ['nullable', 'max:255', 'same:passwordConfirmation', new Password()],
            'passwordConfirmation' => 'nullable|max:255|same:password',
            'groups' => 'nullable|array',
            'permissions' => 'nullable|array',
            'forceChangePassword' => 'nullable|boolean',
            'forceActivateTwoFactor' => 'nullable|boolean',
            'disabled' => 'nullable|boolean',
        ]);

        try {
            $user = User::findOrFail($this->userId);

            $user->update([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'username' => $this->username,
                'email' => $this->email,
                'force_change_password' => $this->forceChangePassword,
                'force_activate_two_factor' => $this->forceActivateTwoFactor,
                'disabled' => $this->disabled,
            ]);

            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            $user->syncRoles($this->groups);
            $user->syncPermissions($this->permissions);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('adminmodule::users.update_user.notifications.user_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.users'), navigate: true);
    }

    #[On('clearForm')]
    public function clearForm()
    {
        $this->firstName = null;
        $this->lastName = null;
        $this->username = null;
        $this->email = null;
        $this->password = null;
        $this->passwordConfirmation = null;
        $this->groups = null;
        $this->permissions = null;
        $this->forceChangePassword = null;
        $this->forceActivateTwoFactor = null;
        $this->disabled = null;
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
        return $this->renderView('adminmodule::livewire.users', __('adminmodule::users.tab_title'), 'adminmodule::components.layouts.app');
    }
}
