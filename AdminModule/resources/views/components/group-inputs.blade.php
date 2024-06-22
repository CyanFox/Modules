<div class="grid md:grid-cols-2 gap-4 mt-4">
    <x-input label="{{ __('adminmodule::groups.name') }} *" wire:model="name" required/>

    <x-input label="{{ __('adminmodule::groups.guard_name') }} *" wire:model="guardName" required/>

    <x-input label="{{ __('adminmodule::groups.module') }} *" wire:model="module" required/>

    <x-select.styled label="{{ __('adminmodule::users.permissions') }}" :options="$permissionList"
                     select="label:label|value:value" wire:model="permissions" multiple searchable/>

</div>
