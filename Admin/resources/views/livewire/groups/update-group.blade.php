<div>
    <x-cf.card :title="__('admin::groups.update_group.title')" view-integration="admin.groups.update">
        <form wire:submit="updateGroup">
            <x-admin::inputs.groups/>

            <x-cf.buttons.update target="updateGroup" :update-text="__('admin::groups.update_group.buttons.update_group')"
                                 :back-url="route('admin.groups')"/>
        </form>
    </x-cf.card>
</div>
