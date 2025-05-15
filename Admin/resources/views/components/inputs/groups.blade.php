<div class="grid md:grid-cols-2 gap-4 mb-4">
    <x-input wire:model="name" label="{{ __('admin::groups.name') }}" required/>

    <x-input wire:model="guardName" label="{{ __('admin::groups.guard_name') }}" required/>

</div>

<x-select.multiple
    label="{{ __('admin::groups.permissions') }}"
    :options="
        \Spatie\Permission\Models\Permission::all()
            ->map(fn($permission) => [
                'value' => $permission->name,
                'label' => $permission->name
            ])
            ->toArray()
    "
    wire:model="permissions"
    required
/>
