@php use Modules\Core\Models\Role; @endphp
@php use Modules\Core\Models\Permission; @endphp
<div>
    <div wire:transition>
        <x-cf.card hook="admin.users.update">
            @hook('admin.users.update.form')
            <form wire:submit="updateUser">
                <div class="grid md:grid-cols-2 gap-4">
                    <x-input wire:model="firstName" :label="__('admin::users.first_name')"/>
                    <x-input wire:model="lastName" :label="__('admin::users.last_name')"/>

                    <x-input wire:model="username" :label="__('admin::users.username')" required/>
                    <x-input wire:model="email" type="email" :label="__('admin::users.email')" required/>

                    <x-password wire:model="password" :show-generate="true" :label="__('admin::users.password')"/>
                    <x-password wire:model="passwordConfirmation" :show-generate="true"
                                :label="__('admin::users.confirm_password')"/>

                    <x-select.multiple wire:model="roles" :label="__('admin::users.roles')">
                        @foreach(Role::all() as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </x-select.multiple>

                    <x-select.multiple wire:model="permissions" :label="__('admin::users.permissions')">
                        @foreach(Permission::all() as $permission)
                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                        @endforeach
                    </x-select.multiple>
                </div>

                <div class="space-y-4 mt-4">
                    <x-checkbox wire:model="disabled" label="{{ __('admin::users.disabled') }}"/>
                </div>

                <x-cf.buttons :submit-text="__('admin::users.update.buttons.update')" target="updateUser"/>
            </form>
            @endhook
        </x-cf.card>
    </div>
</div>
