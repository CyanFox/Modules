<div>
    <x-card>
        <x-slot:header>
            <span class="font-bold text-xl">{{ __('adminmodule::groups.title') }}</span>
        </x-slot:header>

        <x-view-integration name="adminmodule.groups.top"/>

        <form wire:submit="createGroup">
            <x-slide id="create-group-slide" size="4xl" blur>
                @csrf
                <x-slot:title>
                    {{ __('adminmodule::groups.create_group.title') }}
                </x-slot:title>

                <x-view-integration name="adminmodule.groups.create.top"/>

                <x-adminmodule::group-inputs :permissionList="$permissionList"/>

                <x-view-integration name="adminmodule.groups.create.bottom"/>

                <x-slot:footer end>
                    <x-button loading="createGroup" type="submit">{{ __('adminmodule::groups.create_group.buttons.create_group') }}</x-button>

                    <x-view-integration name="adminmodule.groups.create.footer"/>
                </x-slot:footer>
            </x-slide>
        </form>

        <form wire:submit="updateGroup">
            <x-slide id="update-group-slide" size="4xl" blur>
                @csrf
                <x-slot:title>
                    {{ __('adminmodule::groups.update_group.title') }}
                </x-slot:title>

                <x-view-integration name="adminmodule.groups.update.top"/>

                <x-adminmodule::group-inputs :permissionList="$permissionList"/>

                <x-view-integration name="adminmodule.groups.update.top"/>

                <x-slot:footer end>
                    <x-button loading="updateGroup" type="submit">{{ __('adminmodule::groups.update_group.buttons.update_group') }}</x-button>


                    <x-view-integration name="adminmodule.groups.update.footer"/>
                </x-slot:footer>
            </x-slide>
        </form>

        @livewire('adminmodule::components.tables.groups-table')

        <x-view-integration name="adminmodule.groups.bottom"/>
    </x-card>
</div>
