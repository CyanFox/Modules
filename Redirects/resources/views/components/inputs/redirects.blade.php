<div class="grid md:grid-cols-3 gap-4 mb-4">
    <x-input wire:model="from" label="{{ __('redirects::redirects.from') }}" type="url" required/>
    <x-input wire:model="to" label="{{ __('redirects::redirects.to') }}" type="url" required/>

    <x-select wire:model="statusCode" label="{{ __('redirects::redirects.status_code') }}" required>
        @foreach ([301, 302, 303, 307, 308] as $code)
            <option value="{{ $code }}">{{ __('redirects::redirects.status_codes.'. $code) }}</option>
        @endforeach
    </x-select>

</div>

<div class="mb-4 space-y-4">
    <x-checkbox wire:model="active" label="{{ __('redirects::redirects.active') }}"/>
    <x-checkbox wire:model="includeQueryString" label="{{ __('redirects::redirects.include_query_string') }}"/>
    <x-checkbox wire:model.live="internal" label="{{ __('redirects::redirects.internal') }}"/>
</div>

@if($internal)
    <x-card.title>
        {{ __('redirects::redirects.access') }}
    </x-card.title>

    <div class="grid md:grid-cols-3 gap-4 mb-4">
        <x-select.multiple
            label="{{ __('redirects::redirects.groups') }}"
            :options="
        \Modules\Auth\Models\Role::all()
            ->map(fn($group) => [
                'value' => $group->id,
                'label' => $group->name
            ])
            ->toArray()
    "
            wire:model="accessGroups"
        />

        <x-select.multiple
            label="{{ __('redirects::redirects.permissions') }}"
            :options="
        \Modules\Auth\Models\Permission::all()
            ->map(fn($permission) => [
                'value' => $permission->id,
                'label' => $permission->name
            ])
            ->toArray()
    "
            wire:model="accessPermissions"
        />

        <x-select.multiple
            label="{{ __('redirects::redirects.users') }}"
            :options="
        \Modules\Auth\Models\User::all()
            ->map(fn($user) => [
                'value' => $user->id,
                'label' => $user->username
            ])
            ->toArray()
    "
            wire:model="accessUsers"
        />
    </div>
@endif

<x-card.title>
    {{ __('redirects::redirects.edit_access') }}
</x-card.title>

<div class="grid md:grid-cols-3 gap-4 mb-4">
    <x-select.multiple
        label="{{ __('redirects::redirects.groups') }}"
        :options="
        \Modules\Auth\Models\Role::all()
            ->map(fn($group) => [
                'value' => $group->id,
                'label' => $group->name
            ])
            ->toArray()
    "
        wire:model="editAccessGroups"
    />

    <x-select.multiple
        label="{{ __('redirects::redirects.permissions') }}"
        :options="
        \Modules\Auth\Models\Permission::all()
            ->map(fn($permission) => [
                'value' => $permission->id,
                'label' => $permission->name
            ])
            ->toArray()
    "
        wire:model="editAccessPermissions"
    />

    <x-select.multiple
        label="{{ __('redirects::redirects.users') }}"
        :options="
        \Modules\Auth\Models\User::all()
            ->map(fn($user) => [
                'value' => $user->id,
                'label' => $user->username
            ])
            ->toArray()
    "
        wire:model="editAccessUsers"
    />

</div>
