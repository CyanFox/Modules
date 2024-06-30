@props([
    'label' => null,
    'route' => null,
    'url' => null,
    'icon' => null,
    'navigate' => true,
])

<a class="flex items-center w-full h-12 px-3.5 mt-2 rounded dark:hover:bg-dark-600 hover:bg-gray-200 {{ request()->routeIs($route . '*') ? 'dark:bg-dark-600 bg-gray-200' : '' }}"
   href="{{ $url ?? route($route) }}"
        {{ $navigate ? 'wire:navigate' : '' }}>
    <i class="{{ $icon }} dark:text-white"></i>
    <span class="ml-2 text-sm font-medium dark:text-white text-hidden">{{ $label }}</span>
    {{ $slot }}
</a>
