<div>
    <x-cf.card :title="__('redirects::redirects.create_redirect.tab_title')" view-integration="redirects.create">
        <form wire:submit="createRedirect">
            <x-redirects::inputs.redirects :internal="$internal"/>

            <x-cf.buttons.create target="createRedirect" :create-text="__('redirects::redirects.create_redirect.buttons.create_redirect')"
                                 :back-url="route('redirects')"/>
        </form>
    </x-cf.card>
</div>
