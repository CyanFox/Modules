<div>
    <x-card>
        <x-slot:header>
            <span class="font-bold text-xl">{{ __('adminmodule::users.title') }}</span>
        </x-slot:header>

        <x-view-integration name="adminmodule.users.top"/>

        <form wire:submit="createUser">
            <x-slide id="create-user-slide" size="4xl" blur>
                @csrf
                <x-slot:title>
                    {{ __('adminmodule::users.create_user.title') }}
                </x-slot:title>

                <x-view-integration name="adminmodule.users.create.top"/>

                <x-adminmodule::user-inputs :passwordRules="$passwordRules" :passwordRequired="true" :groupList="$groupList"
                                            :permissionList="$permissionList"/>

                <x-view-integration name="adminmodule.users.create.bottom"/>

                <x-slot:footer end>
                    <x-button loading="createUser" type="submit">{{ __('adminmodule::users.create_user.buttons.create_user') }}</x-button>

                    <x-view-integration name="adminmodule.users.create.footer"/>
                </x-slot:footer>
            </x-slide>
        </form>

        <form wire:submit="updateUser">
            <x-slide id="update-user-slide" size="4xl" blur>
                @csrf
                <x-slot:title>
                    {{ __('adminmodule::users.update_user.title') }}
                </x-slot:title>

                <x-view-integration name="adminmodule.users.update.top"/>

                <x-adminmodule::user-inputs :passwordRules="$passwordRules" :passwordRequired="false" :groupList="$groupList"
                                            :permissionList="$permissionList"/>

                <x-view-integration name="adminmodule.users.update.bottom"/>

                <x-slot:footer end>
                    <x-button loading="updateUser" type="submit">{{ __('adminmodule::users.update_user.buttons.update_user') }}</x-button>

                    <x-view-integration name="adminmodule.users.update.footer"/>
                </x-slot:footer>
            </x-slide>
        </form>

        @livewire('adminmodule::components.tables.users-table')

        <x-view-integration name="adminmodule.users.bottom"/>
    </x-card>
</div>
