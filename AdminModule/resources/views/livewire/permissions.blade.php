<div>
    <x-card>
        <x-slot:header>
            <span class="font-bold text-xl">{{ __('adminmodule::permissions.title') }}</span>

            <x-view-integration name="adminmodule.permissions.title"/>
        </x-slot:header>

        <x-view-integration name="adminmodule.permissions.header"/>

        @can('adminmodule.permissions.create')
            <form wire:submit="createPermission">
                <x-slide id="create-permission-slide" size="4xl" blur>
                    @csrf
                    <x-slot:title>
                        {{ __('adminmodule::permissions.create_permission.title') }}

                        <x-view-integration name="adminmodule.permissions.create.title"/>
                    </x-slot:title>

                    <x-adminmodule::permission-inputs/>

                    <x-view-integration name="adminmodule.permissions.create.form"/>

                    <x-slot:footer end>
                        <x-button loading="createPermission"
                                  type="submit">{{ __('adminmodule::permissions.create_permission.buttons.create_permission') }}</x-button>

                        <x-view-integration name="adminmodule.permissions.create.buttons"/>
                    </x-slot:footer>

                    <x-view-integration name="adminmodule.permissions.create.footer"/>
                </x-slide>
            </form>
        @endcan

        @can('adminmodule.permissions.update')
            <form wire:submit="updatePermission">
                <x-slide id="update-permission-slide" size="4xl" blur>
                    @csrf
                    <x-slot:title>
                        {{ __('adminmodule::permissions.update_permission.title') }}

                        <x-view-integration name="adminmodule.permissions.update.title"/>
                    </x-slot:title>

                    <x-adminmodule::permission-inputs />

                    <x-view-integration name="adminmodule.permission.update.form"/>

                    <x-slot:footer end>
                        <x-button loading="updatePermission"
                                  type="submit">{{ __('adminmodule::permissions.update_permission.buttons.update_permission') }}</x-button>

                        <x-view-integration name="adminmodule.permission.update.buttons"/>
                    </x-slot:footer>

                    <x-view-integration name="adminmodule.permission.update.footer"/>
                </x-slide>
            </form>
        @endcan

        @livewire('adminmodule::components.tables.permissions-table')

        <x-view-integration name="adminmodule.permissions.footer"/>
    </x-card>
</div>
