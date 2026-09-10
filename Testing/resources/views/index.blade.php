<x-testing::layouts.master>
    <h1>Hello World</h1>

    @vite(\Nwidart\Modules\Module::getAssets())

    <p>Module: {!! config('testing.name') !!}</p>
</x-testing::layouts.master>
