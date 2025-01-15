<div class="grid md:grid-cols-2 gap-4 mb-4">
    <x-input wire:model="name" required>
        {{ __('admin::permissions.name') }}
    </x-input>

    <x-input wire:model="guardName" required>
        {{ __('admin::permissions.guard_name') }}
    </x-input>

</div>
