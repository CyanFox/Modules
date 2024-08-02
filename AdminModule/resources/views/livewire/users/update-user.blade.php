<div>
    <x-cf.card :title="__('adminmodule::users.update_user.title')" view-integration="adminmodule.users.update">
        <form wire:submit="updateUser">
            <x-adminmodule::user-inputs :passwordRules="$passwordRules" :passwordRequired="false"
                                        :groupList="$groupList"
                                        :permissionList="$permissionList"/>

            <x-actions.buttons.update target="updateUser" :back-url="route('admin.users')"/>
        </form>
    </x-cf.card>
</div>
