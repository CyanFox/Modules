<div>
    <x-cf.card :title="__('adminmodule::permissions.update_permission.title')" view-integration="adminmodule.users.update">
        <form wire:submit="updatePermission">
            <x-adminmodule::permission-inputs/>

            <x-actions.buttons.update target="updatePermission" :back-url="route('admin.permissions')"/>
        </form>
    </x-cf.card>
</div>
