<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      @if(auth()->user()->theme === 'dark')
          class="dark bg-neutral-900"
      @else
          class="bg-white"
    @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <link rel="icon" href="{{ settings('internal.app.logo', config('settings.logo_path')) }}" type="image/x-icon">

    @filamentStyles
    @vite('resources/css/app.css')
    @livewireStyles

    <title>{{ ($title ?? '') . ' · ' . settings('internal.app.name', config('app.name')) }}</title>
</head>
<body>
@livewire('spotlight-search')

<x-admin::navigation.sidebar>
    {{ $slot }}
</x-admin::navigation.sidebar>

@persist('notifications')
<x-toaster-hub/>
@endpersist

@livewireScripts
@filamentScripts
@livewire('wire-elements-modal')
@vite('resources/js/app.js')
<script src="{{ asset('js/logger.js') }}"></script>
</body>
</html>
