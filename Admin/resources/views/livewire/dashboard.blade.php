<div>
    @if($isDevVersion)
        <div class="mb-4">
            <x-alert class="flex justify-center" color="warning">
                {{ __('admin::dashboard.dev_version') }}
            </x-alert>
        </div>

        <x-view-integration name="admin.dashboard.versions.dev"/>
    @endif

    <x-view-integration name="admin.dashboard.dashboard"/>
</div>
