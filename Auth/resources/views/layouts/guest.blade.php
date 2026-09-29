<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark bg-surface-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ settings('app.logo') }}" type="image/x-icon">

    <title>{{ ($title ?? '') . ' · ' . settings('app.name', config('app.name')) }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body>
{{ $slot }}

@livewireScripts
</body>
</html>
