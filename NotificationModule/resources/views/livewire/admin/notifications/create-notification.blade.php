<div>
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <span class="font-bold text-xl">{{ __('notificationmodule::notifications.create_notification.title') }}</span>
            <div class="divider"></div>

            <x-form wire:submit="createNotification">
                @csrf

                <div class="grid md:grid-cols-2 gap-4 mt-4">
                    <x-input label="{{ __('notificationmodule::notifications.title') }}"
                             class="input input-bordered w-full" wire:model="title" required/>

                    <x-select label="{{ __('notificationmodule::notifications.type') }}" wire:model="type"
                              class="select select-bordered"
                              :options="
                                  [['id' => 'info', 'name' => __('notificationmodule::notifications.types.info')],
                                  ['id' => 'update', 'name' => __('notificationmodule::notifications.types.update')],
                                  ['id' => 'success', 'name' => __('notificationmodule::notifications.types.success')],
                                  ['id' => 'warning', 'name' => __('notificationmodule::notifications.types.warning')],
                                  ['id' => 'danger', 'name' => __('notificationmodule::notifications.types.danger')]]"
                              required></x-select>

                    <x-select label="{{ __('notificationmodule::notifications.dismissible') }}" wire:model="dismissible"
                              class="select select-bordered"
                              :options="
                                  [['id' => '1', 'name' => __('messages.yes')],
                                  ['id' => '0', 'name' => __('messages.no')]]"
                              required></x-select>

                    <x-select label="{{ __('notificationmodule::notifications.location') }}" wire:model="location"
                              class="select select-bordered"
                              :options="
                                  [['id' => 'home', 'name' => __('notificationmodule::notifications.locations.home')],
                                  ['id' => 'notificationsTab', 'name' => __('notificationmodule::notifications.locations.notificationsTab')]]"
                              required></x-select>
                </div>

                {{ $this->getForm('messageContent') }}

                <div class="overflow-x-auto mt-4">
                    <div class="grid lg:grid-cols-3 md:grid-cols-1 gap-4">
                        @foreach($uploadedAttachments as $path)
                            @php
                                $file = basename($path);
                                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                            @endphp
                            <div class="card bordered">
                                <figure class="mt-4">

                                    @if($fileExtension === 'png' || $fileExtension === 'jpg' || $fileExtension === 'jpeg')
                                        <img src="{{ asset('storage/' . $path) }}" alt="{{ $file }}"
                                             class="w-32 h-32 object-cover">
                                    @else
                                        <i class="icon-file text-7xl"></i>
                                    @endif
                                </figure>
                                <div class="card-body flex flex-col items-center justify-center">
                                    <p class="card-title">{{ $file }}</p>
                                </div>
                                <div class="justify-end card-actions mb-4 mr-4">
                                    <button type="button" class="btn btn-outline btn-error"
                                            wire:click="removeAttachmentFromTemp('{{ $file }}')">
                                        <i class="icon-x"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 overflow-x-auto">

                        <x-file label="{{ __('notificationmodule::notifications.attachments') }}"
                                wire:model="attachments"
                                multiple="">
                        </x-file>
                    </div>


                    <div class="md:flex gap-2">
                        <x-button type="button"
                                  wire:click="uploadAttachmentsToTemp"
                                  class="btn btn-info mt-3" spinner>
                            {{ __('notificationmodule::notifications.buttons.upload_attachments') }}
                        </x-button>

                        <x-button type="button"
                                  wire:click="$dispatch('openModal', { component: 'components.modals.icon-selector' })"
                                  class="btn btn-ghost btn-outline mt-3" spinner>
                            <i class="{{ $icon }}"></i>
                        </x-button>
                    </div>
                </div>

                <div class="divider mt-4"></div>

                <div class="mt-2 flex justify-start gap-3">
                    <a class="btn btn-neutral" type="button"
                       href="{{ route('admin.notifications') }}">{{ __('messages.buttons.back') }}</a>

                    <x-button class="btn btn-success"
                              type="submit" spinner="createNotification">
                        {{ __('notificationmodule::notifications.create_notification.buttons.create_notification') }}
                    </x-button>
                </div>
            </x-form>
        </div>
    </div>
</div>
