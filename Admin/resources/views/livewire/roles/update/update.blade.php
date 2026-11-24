@php use Modules\Core\Models\Permission; @endphp
<div>
    <div wire:transition>
        <x-cf.card hook="admin.roles.update">
            @hook('admin.roles.update.form')
            <form wire:submit="updateRole">
                <div class="grid md:grid-cols-2 gap-4">
                    <x-input wire:model="name" :label="__('admin::roles.name')" required/>
                    <x-input wire:model="guardName" :label="__('admin::roles.guard_name')"
                             :hint="__('admin::roles.guard_name_hint')" required/>
                </div>

                <div class="space-y-4 mt-4">
                    <x-select.multiple wire:model="permissions" :label="__('admin::roles.permissions')">
                        @foreach(Permission::all() as $permission)
                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                        @endforeach
                    </x-select.multiple>
                </div>

                <x-cf.buttons :submit-text="__('admin::roles.update.buttons.update')" target="updateRole"/>
            </form>
            @endhook
        </x-cf.card>
    </div>
</div>
