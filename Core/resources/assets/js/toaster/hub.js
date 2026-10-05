import {Config} from '../../../../../../vendor/masmerise/livewire-toaster/resources/js/config';
import {Toast} from '../../../../../../vendor/masmerise/livewire-toaster/resources/js/toast';

export function Hub(Alpine) {
    Alpine.data('toasterHub', (initialToasts, config) => {
        config = Config.fromJson(config);

        return {
            _toasts: [],

            get toasts() {
                const toasts = this._toasts.filter(t => !t.trashed);

                if (this._toasts.length && !toasts.length) {
                    this.$nextTick(() => {
                        this._toasts = [];
                    });
                }

                return toasts;
            },

            init() {
                document.addEventListener('toaster:received', event => {
                    this.processToast(event);
                });

                document.addEventListener('toaster:clear', event => {
                    if (event.detail?.type) {
                        this.toasts.filter(t => t.type === event.detail.type).forEach(t => t.dispose());
                    } else {
                        this.toasts.forEach(t => t.dispose());
                    }
                });

                initialToasts.map(Toast.fromJson).forEach(toast => this.show(toast));
            },

            processToast(data) {
                const toast = Toast.fromJson({duration: config.duration, ...data.detail});

                if (config.replace) {
                    this.toasts.filter(t => t.equals(toast)).forEach(t => t.dispose());
                } else if (config.suppress && this.toasts.some(t => t.equals(toast))) {
                    return;
                }

                this.show(toast);
            },

            show(toast) {
                toast = Alpine.reactive(toast);
                toast.runAfterDuration(toast => toast.dispose());

                if (config.alignment.isTop()) {
                    this._toasts.unshift(toast);
                } else {
                    this._toasts.push(toast);
                }
            },
        }
    });
}
