import {Hub} from './hub';
import * as Toaster from '../../../../../../vendor/masmerise/livewire-toaster/resources/js/toaster';

window.Toaster = Toaster;

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(Hub);
});
