<div>
    <x-cf.card :title="__('admin::permissions.create_permission.title')" view-integration="admin.permissions.create">
        <form wire:submit="createPermission">
            <x-admin::inputs.permissions/>

            <x-cf.buttons.create target="createPermission" :create-text="__('admin::permissions.create_permission.buttons.create_permission')"
                                 :back-url="route('admin.permissions')"/>
        </form>
    </x-cf.card>
</div>
