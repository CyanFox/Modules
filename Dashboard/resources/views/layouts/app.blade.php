<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="{{ auth()->user()->theme == 'dark' ? 'dark bg-surface-dark' : 'bg-surface' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ settings('app.logo') }}" type="image/x-icon">

    <title>{{ ($title ?? '') . ' · ' . settings('app.name', config('app.name')) }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(\Nwidart\Modules\Module::getAssets())

    @livewireStyles
</head>
<body>

<x-dashboard::sidebar :breadcrumbs="$breadcrumbs ?? []">
    {{ $slot }}
</x-dashboard::sidebar>

@persist('notifications')
<x-toaster-hub view="core::vendor.toaster.hub"/>
@endpersist

@livewireScripts
</body>
</html>
