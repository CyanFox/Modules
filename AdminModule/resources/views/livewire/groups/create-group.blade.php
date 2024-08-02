<div>
    <x-cf.card :title="__('adminmodule::groups.create_group.title')" view-integration="adminmodule.groups.create">
        <form wire:submit="createGroup">
            <x-adminmodule::group-inputs :permissionList="$permissionList"/>

            <x-actions.buttons.create target="createGroup" :back-url="route('admin.groups')"/>
        </form>
    </x-cf.card>
</div>
