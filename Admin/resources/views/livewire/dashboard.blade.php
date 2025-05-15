<div>
    @if($isDevVersion)
        <div class="mb-4">
            <x-alert class="flex justify-center" type="warning">
                {{ __('admin::dashboard.dev_version') }}
            </x-alert>
        </div>

        <x-view-integration name="admin.dashboard.versions.dev"/>
    @endif

    @if(!$upToDate)
        <div class="mb-4">
            <x-alert class="flex justify-center" type="success">
                {{ __('admin::dashboard.update_available', ['current' => $currentBaseVersion, 'remote' => $remoteBaseVersion]) }}
            </x-alert>
        </div>

        <x-view-integration name="admin.dashboard.versions.update"/>
    @endif

    <div class="grid md:grid-cols-2 gap-4 mb-4">
        <x-alert class="flex justify-center" type="info">
            {{ __('admin::dashboard.current_base_version', ['version' => $currentBaseVersion]) }}
        </x-alert>
        <x-alert class="flex justify-center" type="info">
            {{ __('admin::dashboard.remote_base_version', ['version' => $remoteBaseVersion]) }}
        </x-alert>

        <x-view-integration name="admin.dashboard.versions"/>
    </div>


    <x-view-integration name="admin.dashboard.dashboard"/>
</div>
