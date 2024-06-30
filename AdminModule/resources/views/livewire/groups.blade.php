<div>
    <x-card>
        <x-slot:header>
            <span class="font-bold text-xl">{{ __('adminmodule::groups.title') }}</span>
            <x-view-integration name="adminmodule.groups.title"/>
        </x-slot:header>

        <x-view-integration name="adminmodule.groups.header"/>

        @can('adminmodule.groups.create')
            <form wire:submit="createGroup">
                <x-slide id="create-group-slide" size="4xl" blur>
                    @csrf
                    <x-slot:title>
                        {{ __('adminmodule::groups.create_group.title') }}

                        <x-view-integration name="adminmodule.groups.create.title"/>
                    </x-slot:title>

                    <x-adminmodule::group-inputs :permissionList="$permissionList"/>

                    <x-view-integration name="adminmodule.groups.create.form"/>

                    <x-slot:footer end>
                        <x-button loading="createGroup"
                                  type="submit">{{ __('adminmodule::groups.create_group.buttons.create_group') }}</x-button>

                        <x-view-integration name="adminmodule.groups.create.buttons"/>
                    </x-slot:footer>

                    <x-view-integration name="adminmodule.groups.create.footer"/>
                </x-slide>
            </form>
        @endcan

        @can('adminmodule.groups.update')
            <form wire:submit="updateGroup">
                <x-slide id="update-group-slide" size="4xl" blur>
                    @csrf
                    <x-slot:title>
                        {{ __('adminmodule::groups.update_group.title') }}

                        <x-view-integration name="adminmodule.groups.update.title"/>
                    </x-slot:title>

                    <x-adminmodule::group-inputs :permissionList="$permissionList"/>

                    <x-view-integration name="adminmodule.groups.update.form"/>

                    <x-slot:footer end>
                        <x-button loading="updateGroup"
                                  type="submit">{{ __('adminmodule::groups.update_group.buttons.update_group') }}</x-button>

                        <x-view-integration name="adminmodule.groups.update.buttons"/>
                    </x-slot:footer>

                    <x-view-integration name="adminmodule.groups.update.footer"/>
                </x-slide>
            </form>
        @endcan

        @livewire('adminmodule::components.tables.groups-table')

        <x-view-integration name="adminmodule.groups.footer"/>
    </x-card>
</div>
