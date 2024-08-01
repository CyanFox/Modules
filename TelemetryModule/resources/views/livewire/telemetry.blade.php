<div @if(!module()->getModule('DashboardModule')->isEnabled())
         class="md:m-10 m-5"
    @endif>
    <x-card>
        <x-slot:header>
            <span class="font-bold text-xl">{{ __('telemetrymodule::telemetry.title') }}</span>

            <x-view-integration name="telemetrymodules.telemetry.title"/>
        </x-slot:header>

        <x-view-integration name="telemetrymodules.telemetry.header"/>

        @livewire('telemetrymodule::components.tables.telemetry-table')

        <x-view-integration name="telemetrymodules.telemetry.footer"/>
    </x-card>
</div>
