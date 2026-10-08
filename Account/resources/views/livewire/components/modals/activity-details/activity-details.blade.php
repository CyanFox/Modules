<div>
    <x-modal.header>
        {{ __('account::modals.activity_details.title') }}
    </x-modal.header>

    <div class="grid md:grid-cols-2 gap-4 p-4">
        <div class="flex flex-col overflow-x-auto">
            <span class="text-2xl">{{ __('account::modals.activity_details.old_values') }}</span>
            <x-divider/>
            @php
                function compareValues($val1, $val2)
                {
                    if (is_array($val1) && is_array($val2)) {
                        return json_encode($val1) === json_encode($val2);
                    }
                    return (string)$val1 === (string)$val2;
                }
            @endphp
            <div class="overflow-x-auto">
                <code>
                    @if($oldValues)
                        @foreach($oldValues as $key => $value)
                            @if(!empty($value))
                                {{ $key }}: <span
                                    class="{{ array_key_exists($key, $newValues) && !compareValues($value, $newValues[$key]) ? 'bg-danger/30 dark:bg-danger/50' : '' }}">
                        @if(is_array($value))
                                        {{ json_encode($value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                        </span><br>
                            @endif
                        @endforeach
                    @endif
                </code>
            </div>
        </div>
        <div class="flex flex-col overflow-x-auto">
            <span class="text-2xl">{{ __('account::modals.activity_details.new_values') }}</span>
            <x-divider/>
            <div class="overflow-x-auto">
                <code>
                    @if($newValues)
                        @foreach($newValues as $key => $value)
                            @if(!empty($value))
                                {{ $key }}: <span
                                    class="{{ array_key_exists($key, $oldValues) && !compareValues($value, $oldValues[$key]) ? 'bg-success/30 dark:bg-success/50' : '' }}">
                        @if(is_array($value))
                                        {{ json_encode($value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                        </span><br>
                            @endif
                        @endforeach
                    @endif
                </code>
            </div>
        </div>
    </div>
</div>
