<div>
    <x-cf.card :title="__('adminmodule::groups.update_group.title')" view-integration="adminmodule.groups.update">
        <form wire:submit="updateGroup">
            <x-adminmodule::group-inputs :permissionList="$permissionList"/>

            <x-actions.buttons.update target="updateGroup" :back-url="route('admin.groups')"/>
        </form>
    </x-cf.card>
</div>
