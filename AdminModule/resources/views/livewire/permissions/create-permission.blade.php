<div>
    <x-cf.card :title="__('adminmodule::permissions.create_permission.title')" view-integration="adminmodule.permissions.create">
        <form wire:submit="createPermission">
            <x-adminmodule::permission-inputs/>

            <x-actions.buttons.create target="createPermission" :back-url="route('admin.permissions')"/>
        </form>
    </x-cf.card>
</div>
