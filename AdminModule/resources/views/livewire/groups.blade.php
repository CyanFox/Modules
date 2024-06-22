<div>
    <x-card>
        <x-slot:header>
            <span class="font-bold text-xl">{{ __('adminmodule::groups.title') }}</span>
        </x-slot:header>


        <form wire:submit="createGroup">
            <x-slide id="create-group-slide" size="4xl" blur>
                @csrf
                <x-slot:title>
                    {{ __('adminmodule::groups.create_group.title') }}
                </x-slot:title>

                <x-adminmodule::group-inputs :permissionList="$permissionList"/>

                <x-slot:footer end>
                    <x-button loading="createGroup" type="submit">{{ __('adminmodule::groups.create_group.buttons.create_group') }}</x-button>
                </x-slot:footer>
            </x-slide>
        </form>

        <form wire:submit="updateGroup">
            <x-slide id="update-group-slide" size="4xl" blur>
                @csrf
                <x-slot:title>
                    {{ __('adminmodule::groups.update_group.title') }}
                </x-slot:title>

                <x-adminmodule::group-inputs :permissionList="$permissionList"/>

                <x-slot:footer end>
                    <x-button loading="updateGroup" type="submit">{{ __('adminmodule::groups.update_group.buttons.update_group') }}</x-button>
                </x-slot:footer>
            </x-slide>
        </form>

        @livewire('adminmodule::components.tables.groups-table')
    </x-card>
</div>
