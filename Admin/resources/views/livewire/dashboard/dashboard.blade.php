<div>
    <div class="space-y-6" wire:transition>
        <div class="flex flex-wrap gap-4">
            @foreach([
                ['key' => 'users', 'icon' => 'icon-users'],
                ['key' => 'roles', 'icon' => 'icon-shield-user'],
                ['key' => 'permissions', 'icon' => 'icon-key-round'],
                ['key' => 'modules', 'icon' => 'icon-boxes'],
            ] as $stat)
                @can('admin.' . $stat['key'])
                    <div class="w-full flex-1">
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
                    </div>
                @endcan
            @endforeach
        </div>
    </div>
</div>
