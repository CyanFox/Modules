<div>
    <x-cf.card :title="__('redirects::redirects.update_redirect.tab_title')" view-integration="redirects.update">
        <form wire:submit="updateRedirect">
            <x-redirects::inputs.redirects :internal="$internal"/>

            <x-cf.buttons.update target="updateAnnouncement" :update-text="__('redirects::redirects.update_redirect.buttons.update_redirect')"
                                 :back-url="route('redirects')"/>
        </form>
    </x-cf.card>
</div>
