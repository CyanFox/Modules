<?php

namespace Modules\Admin\Livewire\Users;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\User;

class UpdateUser extends CFComponent
{
    use WithCustomLivewireException;

    public $userId;

    public $user;

    public $firstName;

    public $lastName;

    public $username;

    public $email;

    public $password;

    public $confirmPassword;

    public $forceActivateTwoFactor;

    public $forceChangePassword;

    public $groups;

    public $permissions;

    public $disabled;

    public function updateUser()
    {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'nullable',
            'confirmPassword' => 'nullable|same:password',
            'forceActivateTwoFactor' => 'nullable|boolean',
            'forceChangePassword' => 'nullable|boolean',
            'groups' => 'nullable|array',
            'permissions' => 'nullable|array',
            'disabled' => 'nullable|boolean',
        ]);

        $this->user->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
            'force_activate_two_factor' => $this->forceActivateTwoFactor,
            'force_change_password' => $this->forceChangePassword,
            'disabled' => $this->disabled,
        ]);

        if ($this->password) {
            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
        }

        $this->user->roles()->sync($this->groups);

        $this->user->permissions()->sync($this->permissions);

        Notification::make()
            ->title(__('admin::users.create_user.notifications.user_created'))
            ->success()
            ->send();

        $this->redirect(route('admin.users'), true);
    }

    public function mount()
    {
        try {
            $this->user = User::findOrFail($this->userId);
        } catch (Exception) {
            abort(404);
        }

        $this->firstName = $this->user->first_name;
        $this->lastName = $this->user->last_name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->forceActivateTwoFactor = $this->user->force_activate_two_factor;
        $this->forceChangePassword = $this->user->force_change_password;
        $this->disabled = $this->user->disabled;
        $this->groups = $this->user->roles->pluck('id')->toArray();
        $this->permissions = $this->user->permissions->pluck('id')->toArray();
    }

    public function render()
    {
        return $this->renderView('admin::livewire.users.update-user', __('admin::users.update_user.tab_title'), 'admin::components.layouts.app');
    }
}
