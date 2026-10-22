<?php

use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component {

    public $userId;
    public $user;

    public $firstName;
    public $lastName;
    public $username;
    public $email;
    public $password;
    public $passwordConfirmation;
    public $roles = [];
    public $permissions = [];
    public $disabled;

    public function updateUser()
    {
        $this->validate([
            'firstName' => 'nullable|string|max:255',
            'lastName' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->user->id,
            'email' => 'required|email|max:255',
            'password' => ['nullable', 'string', Password::defaults()],
            'passwordConfirmation' => 'same:password',
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $this->user->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'disabled' => $this->disabled,
        ]);

        $this->user->syncRoles($this->roles);
        $this->user->syncPermissions($this->permissions);

        Toaster::success(__('admin::users.update.notifications.updated'));

        $this->redirect(route('admin.users'), true);
    }

    public function mount()
    {
        $this->user = userModel()->find($this->userId);
        if (!$this->user) {
            abort(404);
        }

        $this->firstName = $this->user->first_name;
        $this->lastName = $this->user->last_name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->disabled = $this->user->disabled;
        $this->roles = $this->user->roles->pluck('name')->toArray();
        $this->permissions = $this->user->permissions->pluck('name')->toArray();
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::users.tab_title'), 'url' => route('admin.users')], ['label' => __('admin::users.update.tab_title'), 'last' => true]]])
            ->title(__('admin::users.update.tab_title'));
    }
};
