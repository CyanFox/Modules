<div>
    <x-modal.header>
        {{ __('account::modals.show_login_qr_code.title') }}
    </x-modal.header>

    <div class="p-4 space-y-4">
        <div class="flex justify-center">
            <x-qr-code
                data="{!! url()->temporarySignedRoute('api.auth.login.qrcode', now()->addMinutes(5), ['userId' => auth()->id()]) !!}"
                size="400" margin="2"/>
        </div>
        <span>
            {{ __('account::modals.show_login_qr_code.description') }}
        </span>
    </div>
</div>
