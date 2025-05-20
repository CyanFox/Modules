<div class="grid md:grid-cols-3 gap-4 mb-4">
    <x-input wire:model="title" label="{{ __('announcements::announcements.title') }}" required/>

    <x-input wire:model.live="icon" label="{{ __('announcements::announcements.icon') }}" icon="icon-{{ $icon }}" required>
        <x-slot:hint>
            {!! \Illuminate\Support\Facades\Blade::render(__('announcements::announcements.icon_hint')) !!}
        </x-slot:hint>
    </x-input>

    <x-select wire:model="color" label="{{ __('announcements::announcements.color') }}" required>
        <option value="info">{{ __('announcements::announcements.colors.info') }}</option>
        <option value="success">{{ __('announcements::announcements.colors.success') }}</option>
        <option value="warning">{{ __('announcements::announcements.colors.warning') }}</option>
        <option value="danger">{{ __('announcements::announcements.colors.danger') }}</option>
    </x-select>

</div>

<div class="mb-4 space-y-4">
    <x-textarea
        wire:model="description"
        label="{{ __('announcements::announcements.description') }}"
        rows="5"/>

    <x-file
        wire:model="files"
        multiple
        label="{{ __('announcements::announcements.files') }}"/>

    <x-checkbox wire:model="dismissible" label="{{ __('announcements::announcements.dismissible') }}"/>

    <x-checkbox wire:model="disabled" label="{{ __('announcements::announcements.disabled') }}"/>
</div>

<x-card.title>
    {{ __('announcements::announcements.access') }}
</x-card.title>

<div class="grid md:grid-cols-3 gap-4 mb-4">
    <x-select.multiple
        label="{{ __('announcements::announcements.groups') }}"
        :options="
        \Spatie\Permission\Models\Role::all()
            ->map(fn($group) => [
                'value' => $group->id,
                'label' => $group->name
            ])
            ->toArray()
    "
        wire:model="groups"
    />

    <x-select.multiple
        label="{{ __('announcements::announcements.permissions') }}"
        :options="
        \Spatie\Permission\Models\Permission::all()
            ->map(fn($permission) => [
                'value' => $permission->id,
                'label' => $permission->name
            ])
            ->toArray()
    "
        wire:model="permissions"
    />

    <x-select.multiple
        label="{{ __('announcements::announcements.users') }}"
        :options="
        \Modules\Auth\Models\User::all()
            ->map(fn($user) => [
                'value' => $user->id,
                'label' => $user->username
            ])
            ->toArray()
    "
        wire:model="users"
    />

</div>
