<div>
    <x-cf.card :title="__('admin::permissions.update_permission.title')" view-integration="admin.permissions.update">
        <form wire:submit="updatePermission">
            <x-admin::inputs.permissions/>

            <x-cf.buttons.update target="updateGroup" :update-text="__('admin::permissions.update_permission.buttons.update_permission')"
                                 :back-url="route('admin.permissions')"/>
        </form>
    </x-cf.card>
</div>
