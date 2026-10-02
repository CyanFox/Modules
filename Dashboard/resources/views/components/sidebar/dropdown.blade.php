@props([
    'icon' => null,
    'label' => null,
])

<div x-data="{ isExpanded: $persist(false) }" class="flex flex-col">
    <button type="button" x-on:click="isExpanded = ! isExpanded"
            x-bind:aria-expanded="isExpanded ? 'true' : 'false'"
            class="flex items-center justify-between cursor-pointer rounded-radius gap-2 px-2 py-1.5 font-medium underline-offset-2 focus:outline-hidden focus-visible:underline text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:text-on-surface-dark-strong dark:hover:bg-primary-dark/5">
        <i class="{{ $icon }}"></i>
        <span class="mr-auto text-left">{{ $label }}</span>

        <i x-bind:class="isExpanded ? 'rotate-90' : 'rotate-0'" class="icon-chevron-right transition-transform"></i>
    </button>

    <ul x-cloak x-collapse x-show="isExpanded" class="ml-3">
        {{ $slot }}
    </ul>
</div>
