<div class="grid md:grid-cols-2 gap-4 mt-4">
    <x-input label="{{ __('adminmodule::users.first_name') }} *"
             class="input input-bordered w-full" wire:model="firstName" required/>

    <x-input label="{{ __('adminmodule::users.last_name') }} *"
             class="input input-bordered w-full" wire:model="lastName" required/>

    <x-input label="{{ __('adminmodule::users.username') }} *"
             class="input input-bordered w-full" wire:model="username" required/>

    <x-input label="{{ __('adminmodule::users.email') }} *" type="email"
             class="input input-bordered w-full" wire:model="email" required/>

    <x-password label="{{ __('adminmodule::users.password') . ($passwordRequired ? ' *' : '') }}" generator
                :rules="$passwordRules"
                class="input input-bordered w-full" wire:model="password"
                :required="$passwordRequired" />

    <x-password label="{{ __('adminmodule::users.password_confirmation') . ($passwordRequired ? ' *' : '') }}" generator
                :rules="$passwordRules"
                class="input input-bordered w-full" wire:model="passwordConfirmation"
                :required="$passwordRequired" />

    <x-select.styled label="{{ __('adminmodule::users.groups') }}" :options="$groupList"
                     select="label:label|value:value" wire:model="groups" multiple searchable/>

    <x-select.styled label="{{ __('adminmodule::users.permissions') }}" :options="$permissionList"
                     select="label:label|value:value" wire:model="permissions" multiple searchable/>


    <div class="space-y-4">
        <x-checkbox label="{{ __('adminmodule::users.force_activate_two_factor') }}"
                    wire:model="forceActivateTwoFactor" class="checkbox-info" left tight/>

        <x-checkbox label="{{ __('adminmodule::users.force_change_password') }}"
                    wire:model="forceChangePassword" class="checkbox-info" left tight/>

        <x-checkbox label="{{ __('adminmodule::users.disabled') }}"
                    wire:model="disabled" class="checkbox-info" left tight/>
    </div>

</div>
