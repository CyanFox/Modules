<div>
    <div class="grid md:grid-cols-3 gap-4 mb-4">
        <x-alert>
            {{ __('adminmodule::dashboard.current.template_version', ['version' => $currentTemplateVersion]) }}
        </x-alert>
        <x-alert>
            {{ __('adminmodule::dashboard.current.project_version', ['version' => $currentProjectVersion]) }}
        </x-alert>
        <x-alert>
            {{ __('adminmodule::dashboard.current.admin_module_version', ['version' => $currentAdminModuleVersion]) }}
        </x-alert>
    </div>

    @if($showUpdateNotification)
        <div class="mb-4 grid md:grid-cols-2 gap-4">
            @if(!$isTemplateUpToDate)
                <x-alert color="red">
                    {{ __('adminmodule::dashboard.new.template_version', ['old' => $currentTemplateVersion, 'new' => $remoteTemplateVersion]) }}
                </x-alert>
            @else
                <x-alert color="green">
                    {{ __('adminmodule::dashboard.up_to_date.template_version') }}
                </x-alert>
            @endif

            @if(!$isProjectUpToDate)
                <x-alert color="red">
                    {{ __('adminmodule::dashboard.new.project_version', ['old' => $currentProjectVersion, 'new' => $remoteProjectVersion]) }}
                </x-alert>
            @else
                <x-alert color="green">
                    {{ __('adminmodule::dashboard.up_to_date.project_version') }}
                </x-alert>
            @endif
        </div>
    @endif

    @if($isDevVersion)
        <div class="my-4">
            <x-alert color="yellow">
                {{ __('adminmodule::dashboard.dev_version') }}
            </x-alert>
        </div>
    @endif

    <div class="grid grid-cols-1">
        <x-button color="green" wire:click="checkForUpdates" loading>
            {{ __('adminmodule::dashboard.buttons.check_for_updates') }}
        </x-button>
    </div>

    <div class="flex flex-wrap gap-4 mt-4">
        <a href="#" class="flex-grow" wire:navigate>
            <x-card>
                <div class="flex justify-center items-center">
                    <i class="icon-users text-2xl"></i>
                    <span class="ml-2">{{ __('adminmodule::dashboard.navigation.users') }}</span>
                </div>
            </x-card>
        </a>

        <a href="#" class="flex-grow" wire:navigate>
            <x-card>
                <div class="flex justify-center items-center">
                    <i class="icon-shield text-2xl"></i>
                    <span class="ml-2">{{ __('adminmodule::dashboard.navigation.groups') }}</span>
                </div>
            </x-card>
        </a>

        <a href="#" class="flex-grow" wire:navigate>
            <x-card>
                <div class="flex justify-center items-center">
                    <i class="icon-settings-2 text-2xl"></i>
                    <span class="ml-2">{{ __('adminmodule::dashboard.navigation.settings') }}</span>
                </div>
            </x-card>
        </a>

        <a href="#" class="flex-grow" wire:navigate>
            <x-card>
                <div class="flex justify-center items-center">
                    <i class="icon-boxes text-2xl"></i>
                    <span class="ml-2">{{ __('adminmodule::dashboard.navigation.modules') }}</span>
                </div>
            </x-card>
        </a>
    </div>

</div>
