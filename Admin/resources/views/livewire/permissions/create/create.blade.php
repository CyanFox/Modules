<div>
    <div wire:transition>
        <x-cf.card hook="admin.permissions.create">
            @hook('admin.permissions.create.form')
            <form wire:submit="createPermission">
                <div class="grid md:grid-cols-2 gap-4">
                    <x-input wire:model="name" :label="__('admin::permissions.name')" required/>
                    <x-input wire:model="guardName" :label="__('admin::permissions.guard_name')"
                             :hint="__('admin::permissions.guard_name_hint')" required/>
                </div>

                <x-cf.buttons :submit-text="__('admin::permissions.create.buttons.create')" target="createPermission"/>
            </form>
            @endhook
        </x-cf.card>
    </div>
</div>
