<div>
    <x-card>
        <x-slot:header>
            <span class="font-bold text-xl">{{ __('adminmodule::permissions.title') }}</span>
        </x-slot:header>

        <x-view-integration name="adminmodule.permissions.top"/>

        @can('adminmodule.permissions.create')
            <form wire:submit="createPermission">
                <x-slide id="create-permission-slide" size="4xl" blur>
                    @csrf
                    <x-slot:title>
                        {{ __('adminmodule::permissions.create_permission.title') }}
                    </x-slot:title>

                    <x-view-integration name="adminmodule.permissions.create.top"/>

                    <x-adminmodule::permission-inputs/>

                    <x-view-integration name="adminmodule.permissions.create.bottom"/>

                    <x-slot:footer end>
                        <x-button loading="createPermission"
                                  type="submit">{{ __('adminmodule::permissions.create_permission.buttons.create_permission') }}</x-button>

                        <x-view-integration name="adminmodule.permissions.create.footer"/>
                    </x-slot:footer>
                </x-slide>
            </form>
        @endcan

        @can('adminmodule.permissions.update')
            <form wire:submit="updatePermission">
                <x-slide id="update-permission-slide" size="4xl" blur>
                    @csrf
                    <x-slot:title>
                        {{ __('adminmodule::permissions.update_permission.title') }}
                    </x-slot:title>

                    <x-view-integration name="adminmodule.permissions.update.top"/>

                    <x-adminmodule::permission-inputs />

                    <x-view-integration name="adminmodule.permission.update.top"/>

                    <x-slot:footer end>
                        <x-button loading="updatePermission"
                                  type="submit">{{ __('adminmodule::permissions.update_permission.buttons.update_permission') }}</x-button>


                        <x-view-integration name="adminmodule.permission.update.footer"/>
                    </x-slot:footer>
                </x-slide>
            </form>
        @endcan

        @livewire('adminmodule::components.tables.permissions-table')

        <x-view-integration name="adminmodule.permissions.bottom"/>
    </x-card>
</div>
