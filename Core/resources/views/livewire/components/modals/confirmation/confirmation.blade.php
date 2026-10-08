<div>
    @if(!$needsPasswordConfirmation || $this->hasPasswordConfirmedSession())
        @if($title)
            <x-modal.header>
                {{ $title }}
            </x-modal.header>
        @endif

        <div class="my-4 space-y-4">
            @if($icon)
                <div class="flex justify-center text-6xl">
                    <i class="{{ $icon }} {{ $iconColor ? $iconColor : 'text-warning' }}"></i>
                </div>
            @endif

            @if($description)
                <div class="px-4 text-center">
                    {{ $description }}
                </div>
            @endif
        </div>

        <x-modal.footer>
            @if($cancel)
                <x-button wire:click="cancelAction" loading="cancelAction" class="w-full" :color="$cancelColor">
                    {{ $cancel }}
                </x-button>
            @endif
            @if($confirm)
                <x-button wire:click="confirmAction" loading="confirmAction" class="w-full" :color="$confirmColor">
                    {{ $confirm }}
                </x-button>
            @endif
        </x-modal.footer>
    @endif
</div>
