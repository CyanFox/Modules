@php
    $isTop = $alignment->is('top');
    $toastSlice = $isTop ? '0, 3' : '-3';
    $expandedOffset = $isTop
        ? '(visibleCount - index - 1) * 72'
        : '(visibleCount - index - 1) * -72';
    $collapsedOffset = $isTop
        ? '(visibleCount - index - 1) * 14'
        : '(visibleCount - index - 1) * -14';
    $zIndex = $isTop ? 'visibleCount - index' : 'index + 1';
@endphp

<div
    role="status"
    id="toaster"
    x-data="toasterHub(@js($toasts), @js($config))"
    @class([
        'fixed z-50 flex w-full flex-col pointer-events-none p-4 sm:p-6',
        'bottom-0' => $alignment->is('bottom'),
        'top-1/2 -translate-y-1/2' => $alignment->is('middle'),
        'top-0' => $alignment->is('top'),
        'items-start rtl:items-end' => $position->is('left'),
        'items-center' => $position->is('center'),
        'items-end rtl:items-start' => $position->is('right'),
    ])
>
    <div
        x-data="{
            expanded: false,
            leaveTimer: null,

            enter(index) {
                clearTimeout(this.leaveTimer);

                const visibleCount = Math.min(this.toasts.length, 3);
                const frontIndex = {{ $alignment->is('top') ? '0' : 'visibleCount - 1' }};

                if (this.expanded || index === frontIndex) {
                    this.expanded = true;
                }
            },

            leave(event) {
                clearTimeout(this.leaveTimer);

                if (event.relatedTarget?.closest('[data-toast], [data-toast-area]')) {
                    return;
                }

                this.leaveTimer = setTimeout(() => {
                    this.expanded = false;
                }, 250);
            },
        }"
        class="pointer-events-none relative w-full max-w-sm"
    >
        <div
            x-show="expanded"
            @mouseenter="clearTimeout(leaveTimer)"
            @mouseleave="leave($event)"
            data-toast-area
            @class([
                'pointer-events-auto absolute inset-x-0 z-0',
                '-top-40 bottom-0' => $alignment->is('bottom'),
                'top-0 -bottom-40' => $alignment->is('top'),
                '-inset-y-40' => $alignment->is('middle'),
            ])
        ></div>

        <div class="grid py-3">
            <template
                x-for="(toast, index) in toasts.slice({{ $toastSlice }})"
                :key="toast.id"
            >
                <div
                    x-show="toast.isVisible"
                    x-data="{ progress: 100 }"
                    x-init="
                        $nextTick(() => {
                            setTimeout(() => {
                                toast.show($el);

                                const duration = toast.duration || 6000;
                                const interval = 50;
                                const step = (100 / duration) * interval;
                                const timer = setInterval(() => {
                                    progress -= step;

                                    if (progress <= 0) {
                                        progress = 0;
                                        clearInterval(timer);
                                    }
                                }, interval);
                            }, 0);
                        });
                    "
                    @mouseenter="enter(index)"
                    @mouseleave="leave($event)"
                    :style="(() => {
                        const visibleCount = Math.min(toasts.length, 3);
                        const offset = expanded
                            ? {{ $expandedOffset }}
                            : {{ $collapsedOffset }};
                        const zIndex = {{ $zIndex }};

                        return `transform: translateY(${offset}px); z-index: ${zIndex};`;
                    })()"
                    @if($alignment->is('bottom'))
                        x-transition:enter-start="translate-y-12 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    @elseif($alignment->is('top'))
                        x-transition:enter-start="-translate-y-12 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    @else
                        x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    @endif
                    x-transition:enter="transition-all ease-out duration-300"
                    x-transition:leave="transition-all ease-in duration-200"
                    x-transition:leave-end="opacity-0 scale-90"
                    data-toast
                    class="relative col-start-1 row-start-1 w-full overflow-hidden rounded-xl border border-outline bg-surface-alt text-on-surface transform-gpu pointer-events-auto transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark"
                >
                    <div class="flex w-full items-center gap-2 p-4">
                        <span class="flex items-center justify-center self-center text-lg">
                            <template x-if="toast.type === 'success'">
                                <i class="icon-check text-success text-lg leading-none"></i>
                            </template>
                            <template x-if="toast.type === 'error'">
                                <i class="icon-x text-danger text-lg leading-none"></i>
                            </template>
                            <template x-if="toast.type === 'info'">
                                <i class="icon-info text-info text-lg leading-none"></i>
                            </template>
                            <template x-if="toast.type === 'warning'">
                                <i class="icon-triangle-alert text-warning text-lg leading-none"></i>
                            </template>
                        </span>

                        <div class="mb-0.5 grid flex-1">
                            <div class="text-sm" x-text="toast.message"></div>
                        </div>

                        @if($closeable)
                            <button
                                type="button"
                                class="flex cursor-pointer items-center"
                                @click="toast.dispose()"
                                aria-label="Close notification"
                            >
                                <i class="icon-x"></i>
                            </button>
                        @endif
                    </div>

                    <div class="h-0.5 w-full bg-gray-200 dark:bg-gray-700">
                        <div
                            class="h-full transition-all duration-100 ease-linear"
                            :class="{
                                'bg-success': toast.type === 'success',
                                'bg-danger': toast.type === 'error',
                                'bg-info': toast.type === 'info',
                                'bg-warning': toast.type === 'warning'
                            }"
                            :style="`width: ${progress}%`"
                        ></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
