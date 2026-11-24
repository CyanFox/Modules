<div
    x-data="{
        init() {
            window.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    this.$wire.open();
                }
                if (e.key === 'Escape' && $wire.isOpen) {
                    this.$wire.close();
                }
                if ($wire.isOpen) {
                    if (e.key === 'ArrowDown') {
                        this.$wire.selectNextResult();
                    } else if (e.key === 'ArrowUp') {
                        this.$wire.selectPreviousResult();
                    } else if (e.key === 'Enter') {
                        this.$wire.selectResult();
                    }
                }
            });
        }
    }"
    class="relative z-50"
>
    <div
        x-cloak
        x-show="$wire.isOpen"
        class="fixed inset-0 z-40 bg-black/20 backdrop-blur-md transition-opacity"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <div
        x-cloak
        x-show="$wire.isOpen"
        class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20"
    >
        <x-card
            @click.away="$wire.close()"
            x-show="$wire.isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="mx-auto max-w-2xl transform overflow-hidden rounded-xl shadow-2xl transition-all"
        >
            <div class="relative p-0.5">
                <x-input
                    wire:model.live="searchTerm"
                    type="text"
                    placeholder="{{ __('core::spotlight.search_placeholder') }}"
                    x-ref="searchInput"
                    @focus="$el.select()"
                    x-init="$watch('$wire.isOpen', value => { if (value) $nextTick(() => $el.focus()) })"
                >
                    <x-slot:hint>
                        <p class="mt-1 text-sm text-on-surface dark:text-on-surface-dark">
                            <x-kbd size="xs">⏎</x-kbd>
                            <span>{{ __('core::spotlight.kbds.open') }}</span>
                            <span class="mx-1">•</span>
                            <x-kbd size="xs">↑↓</x-kbd>
                            <span>{{ __('core::spotlight.kbds.navigate') }}</span>
                            <span class="mx-1">•</span>
                            <x-kbd size="xs">ESC</x-kbd>
                            <span>{{ __('core::spotlight.kbds.close') }}</span>
                        </p>
                    </x-slot:hint>
                </x-input>

                <x-divider class="mt-2"/>
            </div>

            <div class="scroll-py-2 overflow-y-auto p-0.5" id="spotlight-results">
                @if(count($results) > 0)
                    <ul role="listbox" class="space-y-2">
                        @foreach($results as $index => $result)
                            <li
                                wire:key="result-{{ $result['id'] }}"
                                wire:click="selectResult({{ $index }})"
                                class="cursor-pointer p-2 rounded-lg transition-colors duration-150 {{ $selectedIndex === $index ? 'text-on-surface ring-2 ring-on-surface dark:ring-on-surface-dark dark:text-on-surface-dark' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                                role="option"
                                @mouseenter="$wire.selectedIndex = {{ $index }}"
                            >
                                <div
                                    class="flex items-center text-on-surface dark:text-on-surface-dark">
                                    <div
                                        class="flex-shrink-0">
                                        <i class="{{ $result['icon'] }}"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="font-medium truncate">{{ __($result['title']) }}</div>
                                        @if(!empty($result['description']))
                                            <div
                                                class="text-sm truncate">
                                                {{ __($result['description']) }}
                                            </div>
                                        @endif
                                    </div>
                                    @if(isset($result['module']))
                                        <x-badge class="cursor-pointer"
                                                 wire:click="$set('searchTerm', '{{ $result['module'] }}')"
                                                 x-on:click.stop="">
                                            {{ __($result['module']) }}
                                        </x-badge>
                                    @elseif(isset($result['model_type']) && $result['model_type'] !== 'manual')
                                        <x-badge class="cursor-pointer"
                                                 wire:click="$set('searchTerm', '{{ class_basename($result['model_type']) }}')"
                                                 x-on:click.stop="">
                                            {{ class_basename($result['model_type']) }}
                                        </x-badge>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @elseif(empty($searchTerm) && count($staticItems) > 0)
                    <div class="py-14 px-6 text-center text-sm sm:px-14">
                        <span class="text-2xl">
                            <i class="icon-search"></i>
                        </span>
                        <p class="mt-4 font-semibold">{{ __('core::spotlight.search_title') }}</p>
                        <p class="mt-2 text-on-surface dark:text-on-surface-dark">{{ __('core::spotlight.search_description') }}</p>
                    </div>

                    <x-divider/>

                    <div class="mb-2">
                        <h3 class="px-2 pt-1 text-xs font-semibold text-gray-500">{{ __('core::spotlight.quick_access') }}</h3>
                        <ul class="mt-1 space-y-2" role="listbox">
                            @foreach($this->getDisplayItems() as $index => $item)
                                <li
                                    wire:key="result-{{ $item['id'] }}"
                                    wire:click="selectResult({{ $index }})"
                                    class="cursor-pointer p-2 rounded-lg transition-colors duration-150 {{ $selectedIndex === $index ? 'text-on-surface ring-2 ring-on-surface dark:ring-on-surface-dark dark:text-on-surface-dark' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                                    role="option"
                                    @mouseenter="$wire.selectedIndex = {{ $index }}"
                                >
                                    <div
                                        class="flex items-center text-on-surface dark:text-on-surface-dark">
                                        <div
                                            class="flex-shrink-0">
                                            <i class="{{ $item['icon'] }}"></i>
                                        </div>
                                        <div class="ml-1 flex-1">
                                            <div class="font-medium truncate">{{ __($item['title']) }}</div>
                                        </div>
                                        @if(isset($item['module']))
                                            <x-badge class="cursor-pointer"
                                                     wire:click="$set('searchTerm', '{{ $item['module'] }}')"
                                                     x-on:click.stop="">
                                                {{ __($item['module']) }}
                                            </x-badge>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(!empty($searchTerm))
                    <div class="py-14 px-6 text-center text-sm sm:px-14">
                        <span class="text-2xl">
                            <i class="icon-search-x"></i>
                        </span>
                        <p class="mt-4 font-semibold">{{ __('core::spotlight.no_results_title') }}</p>
                        <p class="mt-2 text-on-surface dark:text-on-surface-dark">{{ __('core::spotlight.no_results_description', ['query' => $searchTerm]) }}</p>
                    </div>
                @else
                    <div class="py-14 px-6 text-center text-sm sm:px-14">
                        <span class="text-2xl">
                            <i class="icon-search"></i>
                        </span>
                        <p class="mt-4 font-semibold">{{ __('core::spotlight.search_title') }}</p>
                        <p class="mt-2 text-on-surface dark:text-on-surface-dark">{{ __('core::spotlight.search_description') }}</p>
                    </div>
                @endif
            </div>
        </x-card>
    </div>
</div>
