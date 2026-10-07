@props([
    'date' => null,
    'fallback' => ''
])

<span {{ $attributes }} x-data x-tooltip.raw="{{ formatDateTime($date) }}">
    {{ $date ? carbon()->parse($date)->diffForHumans() : $fallback }}
</span>
