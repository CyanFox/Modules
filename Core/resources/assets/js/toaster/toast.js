import {uuid41} from '../../../../../../vendor/masmerise/livewire-toaster/resources/js//uuid41';

export class Toast {
    constructor(duration, message, type) {
        this.$el = null;
        this.id = uuid41();
        this.isVisible = false;
        this.duration = duration;
        this.message = message;
        this.timeout = null;
        this.trashed = false;
        this.type = type;
    }

    static fromJson(data) {
        return new Toast(data.duration, data.message, data.type);
    }

    dispose() {
        if (this.timeout) {
            clearTimeout(this.timeout);
        }

        this.isVisible = false;
    }

    equals(other) {
        return this.duration === other.duration
            && this.message === other.message
            && this.type === other.type;
    }

    runAfterDuration(callback) {
        this._callback = callback;
        this._startTime = Date.now();
        this.timeout = setTimeout(() => callback(this), this.duration);
    }

    pause() {
        if (this.timeout) {
            clearTimeout(this.timeout);
            this.timeout = null;
            this._pausedAt = Date.now();
            this._elapsed = this._pausedAt - this._startTime;
        }
    }

    resume() {
        if (!this.timeout && this._callback) {
            const remaining = this.duration - this._elapsed;
            if (remaining > 0) {
                this._startTime = Date.now() - this._elapsed;
                this.timeout = setTimeout(() => this._callback(this), remaining);
            } else {
                this._callback(this);
            }
        }
    }

    select(config) {
        return config[this.type];
    }

    show($el) {
        this.$el = $el;
        this.isVisible = true;
    }
}
