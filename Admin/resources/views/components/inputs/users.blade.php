<div class="grid md:grid-cols-2 gap-4 mb-4">
    <x-input wire:model="firstName" label="{{ __('admin::users.first_name') }}" required/>

    <x-input wire:model="lastName" label="{{ __('admin::users.last_name') }}" required/>

    <x-input wire:model="username" label="{{ __('admin::users.username') }}" required/>

    <x-input type="email"
             wire:model="email" label="{{ __('admin::users.email') }}" required/>

    <x-password wire:model="password"
                :showGenerate="true"
                label="{{ __('admin::users.password') }}"
                :required="$passwordRequired"/>

    <x-password wire:model="confirmPassword"
                label="{{ __('admin::users.confirm_password') }}"
                :required="$passwordRequired"/>

    <x-select.multiple
        label="{{ __('admin::users.groups') }}"
        :options="
        \Spatie\Permission\Models\Role::all()
            ->map(fn($group) => [
                'value' => $group->id,
                'label' => $group->name
            ])
            ->toArray()
    "
        wire:model="groups"
    />

    <x-select.multiple
        label="{{ __('admin::users.permissions') }}"
        :options="
        \Spatie\Permission\Models\Permission::all()
            ->map(fn($permission) => [
                'value' => $permission->id,
                'label' => $permission->name
            ])
            ->toArray()
    "
        wire:model="permissions"
    />


    <div class="space-y-4">
        <x-checkbox wire:model="forceActivateTwoFactor" label="{{ __('admin::users.force_activate_two_factor') }}"/>

        <x-checkbox wire:model="forceChangePassword" label="{{ __('admin::users.force_change_password') }}"/>

        <x-checkbox wire:model="disabled" label="{{ __('admin::users.disabled') }}"/>
    </div>

</div>
