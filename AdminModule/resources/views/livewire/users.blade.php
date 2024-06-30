<div>
    <x-card>
        <x-slot:header>
            <span class="font-bold text-xl">{{ __('adminmodule::users.title') }}</span>
            <x-view-integration name="adminmodule.users.title"/>
        </x-slot:header>

        <x-view-integration name="adminmodule.users.header"/>

        @can('adminmodule.users.create')
            <form wire:submit="createUser">
                <x-slide id="create-user-slide" size="4xl" blur>
                    @csrf
                    <x-slot:title>
                        {{ __('adminmodule::users.create_user.title') }}

                        <x-view-integration name="adminmodule.users.create.title"/>
                    </x-slot:title>

                    <x-adminmodule::user-inputs :passwordRules="$passwordRules" :passwordRequired="true"
                                                :groupList="$groupList"
                                                :permissionList="$permissionList"/>

                    <x-view-integration name="adminmodule.users.create.form"/>

                    <x-slot:footer end>
                        <x-button loading="createUser"
                                  type="submit">{{ __('adminmodule::users.create_user.buttons.create_user') }}</x-button>

                        <x-view-integration name="adminmodule.users.create.buttons"/>
                    </x-slot:footer>

                    <x-view-integration name="adminmodule.users.create.footer"/>
                </x-slide>
            </form>
        @endcan

        @can('adminmodule.users.update')
            <form wire:submit="updateUser">
                <x-slide id="update-user-slide" size="4xl" blur>
                    @csrf
                    <x-slot:title>
                        {{ __('adminmodule::users.update_user.title') }}

                        <x-view-integration name="adminmodule.users.update.title"/>
                    </x-slot:title>

                    <x-adminmodule::user-inputs :passwordRules="$passwordRules" :passwordRequired="false"
                                                :groupList="$groupList"
                                                :permissionList="$permissionList"/>

                    <x-view-integration name="adminmodule.users.update.form"/>

                    <x-slot:footer end>
                        <x-button loading="updateUser"
                                  type="submit">{{ __('adminmodule::users.update_user.buttons.update_user') }}</x-button>

                        <x-view-integration name="adminmodule.users.update.buttons"/>
                    </x-slot:footer>

                    <x-view-integration name="adminmodule.users.update.footer"/>
                </x-slide>
            </form>
        @endcan

        @livewire('adminmodule::components.tables.users-table')

        <x-view-integration name="adminmodule.users.footer"/>
    </x-card>
</div>
