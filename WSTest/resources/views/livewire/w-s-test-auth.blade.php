<div class="mx-10 my-10 space-y-4">
    <x-cf.card title="Actions">
        <div class="grid grid-cols-2 gap-4">
            <x-button link="/wstest">
                Goto WSTest
            </x-button>
            <x-button link="/wstest/send/auth" target="_blank">
                Send WS Message
            </x-button>
        </div>
    </x-cf.card>
    <x-cf.card title="Response">
        <div class="p-4">
            <pre>{!! $response !!}</pre>
        </div>
    </x-cf.card>
</div>
