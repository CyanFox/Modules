<div class="grid md:grid-cols-2 gap-4 mt-4">
    <x-input wire:model="firstName" required>
        {{ __('admin::users.first_name') }}
    </x-input>

    <x-input wire:model="lastName" required>
        {{ __('admin::users.last_name') }}
    </x-input>

    <x-input wire:model="username" required>
        {{ __('admin::users.username') }}
    </x-input>

    <x-input type="email"
             wire:model="email" required>
        {{ __('admin::users.email') }}
    </x-input>

    <x-password wire:model="password"
                :required="$passwordRequired">
        {{ __('admin::users.password') }}
    </x-password>

    <x-password wire:model="confirmPassword"
                :required="$passwordRequired">
        {{ __('admin::users.confirm_password') }}
    </x-password>

    <x-select label="{{ __('admin::users.groups') }}"
        wire:model="groups" multiple>
        @foreach(\Spatie\Permission\Models\Role::all() as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
    </x-select>

    <x-select label="{{ __('admin::users.permissions') }}"
                     wire:model="permissions" multiple>
        @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
        @endforeach
    </x-select>


    <div class="space-y-4">
        <x-checkbox wire:model="forceActivateTwoFactor">
            {{ __('admin::users.force_activate_two_factor') }}
        </x-checkbox>

        <x-checkbox wire:model="forceChangePassword">
            {{ __('admin::users.force_change_password') }}
        </x-checkbox>

        <x-checkbox wire:model="disabled">
            {{ __('admin::users.disabled') }}
        </x-checkbox>
    </div>

</div>
