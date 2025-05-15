<div>
    <x-cf.card :title="__('admin::users.create_user.title')" view-integration="admin.users.create">
        <form wire:submit="createUser">
            <x-admin::inputs.users :passwordRequired="true"/>

            <x-cf.buttons.create target="createUser" :create-text="__('admin::users.create_user.buttons.create_user')"
                                 :back-url="route('admin.users')"/>
        </form>
    </x-cf.card>
</div>
