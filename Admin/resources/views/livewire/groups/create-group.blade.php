<div>
    <x-cf.card :title="__('admin::groups.create_group.title')" view-integration="admin.groups.create">
        <form wire:submit="createGroup">
            <x-admin::inputs.groups/>

            <x-cf.buttons.create target="createGroup" :create-text="__('admin::groups.create_group.buttons.create_group')"
                                 :back-url="route('admin.groups')"/>
        </form>
    </x-cf.card>
</div>
