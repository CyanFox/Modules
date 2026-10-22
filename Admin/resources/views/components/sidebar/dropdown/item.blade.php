@props([
    'route' => null,
    'wireNavigate' => true,
    'icon' => null,
    'checkCurrentRoute' => true,
])

<li class="px-1 py-0.5 first:mt-2">
    <a @if($wireNavigate) wire:navigate @endif @if($route) href="{{ route($route) }}" @endif
        {{ $attributes->twMerge(
             'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm underline-offset-2 focus:outline-hidden focus-visible:underline ' .
             (($checkCurrentRoute && $route && request()->routeIs($route . '*'))
                 ? 'text-on-surface-strong bg-primary/10 dark:text-on-surface-dark-strong dark:bg-primary-dark/10' :  'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:text-on-surface-dark-strong dark:hover:bg-primary-dark/5')
        ) }}>
        <i class="{{ $icon }}"></i>
        <span>
        {{ $slot }}
    </span>
    </a>
</li>
