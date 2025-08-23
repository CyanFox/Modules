<?php

namespace Modules\Admin\Livewire\Users;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Illuminate\Support\Facades\Hash;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Actions\Users\CreateUserAction;
use Modules\Auth\Rules\Password;

class CreateUser extends CFComponent
{
    use WithCustomLivewireException;

    public $firstName;

    public $lastName;

    public $username;

    public $email;

    public $password;

    public $confirmPassword;

    public $forceActivateTwoFactor;

    public $forceChangePassword;

    public $groups = [];

    public $permissions = [];

    public $disabled;

    public function createUser()
    {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => ['nullable', new Password],
            'confirmPassword' => 'required|same:password',
            'forceActivateTwoFactor' => 'nullable|boolean',
            'forceChangePassword' => 'nullable|boolean',
            'groups' => 'nullable|array',
            'permissions' => 'nullable|array',
            'disabled' => 'nullable|boolean',
        ]);

        $user = CreateUserAction::run([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'force_activate_two_factor' => $this->forceActivateTwoFactor ?? false,
            'force_change_password' => $this->forceChangePassword ?? false,
            'disabled' => $this->disabled ?? false,
        ]);

        $user->roles()->sync($this->groups);

        $user->permissions()->sync($this->permissions);

        Toaster::success(__('admin::users.create_user.notifications.user_created'));

        $this->redirect(route('admin.users'), true);
    }

    public function render()
    {
        return $this->renderView('admin::livewire.users.create-user', __('admin::users.create_user.tab_title'), 'admin::components.layouts.app');
    }
}
