<div>
    <div class="space-y-6" wire:transition>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach([
                ['key' => 'users', 'icon' => 'icon-users'],
                ['key' => 'roles', 'icon' => 'icon-shield-user'],
                ['key' => 'permissions', 'icon' => 'icon-key-round'],
                ['key' => 'modules', 'icon' => 'icon-boxes'],
            ] as $stat)
                <x-cf.card>
                    <x-card.title :divider="false">
                        {{ __('admin::dashboard.stats.' . $stat['key']) }}
                    </x-card.title>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="mt-2 text-3xl font-semibold">{{ $this->stats[$stat['key']] }}</span>
                        </div>
                        <div class="text-2xl">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </x-cf.card>
            @endforeach
        </div>
    </div>
</div>
