<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      @if(user()->getUser(user()->findUser(auth()->user()->id))->getColorScheme() == 'dark') class="dark bg-gray-700"
      @else class="bg-white" @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? '' }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <tallstackui:script/>
    @filamentStyles
    @vite('resources/css/app.css')
    @livewireStyles
    @livewireScripts
</head>
<body>
@livewire('notifications')

<x-adminmodule::navigation.sidebar>
    {{ $slot }}
</x-adminmodule::navigation.sidebar>

<x-toast/>
<x-dialog/>

@filamentScripts
@vite('resources/js/app.js')
<script src="{{ asset('js/logger.js') }}"></script>
</body>
</html>
