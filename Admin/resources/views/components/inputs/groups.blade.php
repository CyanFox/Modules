<div class="grid md:grid-cols-2 gap-4 mb-4">
    <x-input wire:model="name" required>
        {{ __('admin::groups.name') }}
    </x-input>

    <x-input wire:model="guardName" required>
        {{ __('admin::groups.guard_name') }}
    </x-input>

</div>
<x-select label="{{ __('admin::groups.permissions') }}"
          wire:model="permissions" multiple>
    @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
    @endforeach
</x-select>
