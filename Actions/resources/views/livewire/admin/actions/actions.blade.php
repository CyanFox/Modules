<div>
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <span class="font-bold text-xl">{{ __('actions::actions.title') }}</span>
            <div class="divider"></div>

            <div class="flex flex-wrap gap-4 justify-around">
                <div>
                    <x-button
                        class="btn-primary"
                        label="{{ __('actions::actions.buttons.clear_cache') }}"
                        wire:click="clearCache" spinner></x-button>
                </div>
                <div>
                    <x-button
                        class="btn-primary"
                        label="{{ __('actions::actions.buttons.clear_view_cache') }}"
                        wire:click="clearViewCache" spinner></x-button>
                </div>
                <div>
                    <x-button
                        class="btn-primary"
                        label="{{ __('actions::actions.buttons.clear_activity_log') }}"
                        wire:click="clearActivityLog" spinner></x-button>
                </div>
                <div>
                    <x-button
                        class="btn-primary"
                        label="{{ __('actions::actions.buttons.npm_install') }}"
                        wire:click="npmInstall" spinner></x-button>
                </div>
                <div>
                    <x-button
                        class="btn-primary"
                        label="{{ __('actions::actions.buttons.npm_build') }}"
                        wire:click="npmBuild" spinner></x-button>
                </div>
            </div>

            <div class="divider"></div>

            <span class="font-bold text-xl">{{ __('actions::actions.output') }}</span>

            <div class="overflow-y-auto h-96">
                <pre class="text-sm text-white">{{ $output }}</pre>
            </div>
        </div>
    </div>
</div>
