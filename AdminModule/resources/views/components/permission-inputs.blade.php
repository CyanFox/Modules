<div class="grid md:grid-cols-2 gap-4 my-4">
    <x-input label="{{ __('adminmodule::permissions.name') }} *" wire:model="name" required/>

    <x-input label="{{ __('adminmodule::permissions.guard_name') }} *" wire:model="guardName" required/>

</div>

<div class="mb-4">
    <x-input label="{{ __('adminmodule::permissions.module') }} *" wire:model="module" required/>
</div>
