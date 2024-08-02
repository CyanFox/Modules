<div>
    <x-cf.card :title="__('adminmodule::users.create_user.title')" view-integration="adminmodule.users.create">
        <form wire:submit="createUser">
            <x-adminmodule::user-inputs :passwordRules="$passwordRules" :passwordRequired="true"
                                        :groupList="$groupList"
                                        :permissionList="$permissionList"/>

            <x-actions.buttons.create target="createUser" :back-url="route('admin.users')"/>
        </form>
    </x-cf.card>
</div>
