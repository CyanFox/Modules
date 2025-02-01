<!-- sidebar-item.blade.php -->
@props([
    'icon',
    'label',
    'url' => null,
    'route' => null,
    'external' => false,
])

@if(request()->routeIs($route . '*'))
    <a href="{{ $url ?? route($route) }}"
       @if(!$external) wire:navigate @endif
       class="group relative flex items-center rounded-md bg-black/10 font-medium text-neutral-900 underline-offset-2 focus-visible:underline focus:outline-hidden dark:bg-white/10 dark:text-white overflow-hidden h-9"
       :class="(!sidebarPinned && !sidebarHovered) ? 'md:w-10' : 'w-full px-2'">
        <div class="flex items-center w-full"
             :class="(!sidebarPinned && !sidebarHovered) ? 'md:w-10 md:justify-center' : 'w-full gap-2'">
            <i class="{{ $icon }}"></i>
            <span class="transition-all duration-300 whitespace-nowrap"
                  :class="(!sidebarPinned && !sidebarHovered) ? 'block md:opacity-0 md:w-0' : 'opacity-100 w-auto'">
            {{ $label }}
        </span>
        </div>
    </a>
@else
    <a href="{{ $url ?? route($route) }}"
       @if(!$external) wire:navigate @endif
       class="group relative flex items-center rounded-md font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-hidden dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white overflow-hidden h-9"
       :class="(!sidebarPinned && !sidebarHovered) ? 'md:w-10' : 'w-full px-2'">
        <div class="flex items-center w-full"
             :class="(!sidebarPinned && !sidebarHovered) ? 'md:w-10 md:justify-center' : 'w-full gap-2'">
            <i class="{{ $icon }}"></i>
            <span class="transition-all duration-300 whitespace-nowrap"
                  :class="(!sidebarPinned && !sidebarHovered) ? 'block md:opacity-0 md:w-0' : 'opacity-100 w-auto'">
            {{ $label }}
        </span>
        </div>
    </a>
@endif
