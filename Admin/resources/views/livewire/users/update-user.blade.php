<div>
    <x-cf.card :title="__('admin::users.update_user.title')" view-integration="admin.users.update">
        <form wire:submit="updateUser">
            <x-admin::inputs.users :passwordRequired="true"/>

            <x-cf.buttons.update target="updateUser" :update-text="__('admin::users.update_user.buttons.update_user')"
                                 :back-url="route('admin.users')"/>
        </form>
    </x-cf.card>
</div>
