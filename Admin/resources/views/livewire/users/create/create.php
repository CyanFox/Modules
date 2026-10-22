<?php

use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component {

    public $firstName;
    public $lastName;
    public $username;
    public $email;
    public $password;
    public $passwordConfirmation;
    public $roles = [];
    public $permissions = [];
    public $disabled;

    public function createUser()
    {
        $this->validate([
            'firstName' => 'nullable|string|max:255',
            'lastName' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255',
            'password' => ['required', 'string', Password::defaults()],
            'passwordConfirmation' => 'same:password',
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $user = userModel()->create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'disabled' => $this->disabled,
        ]);

        $user->syncRoles($this->roles);
        $user->syncPermissions($this->permissions);

        Toaster::success(__('admin::users.create.notifications.created'));

        $this->redirect(route('admin.users'), true);
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::users.tab_title'), 'url' => route('admin.users')], ['label' => __('admin::users.create.tab_title'), 'last' => true]]])
            ->title(__('admin::users.create.tab_title'));
    }
};
