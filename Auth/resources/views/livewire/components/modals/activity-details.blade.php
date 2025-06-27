<div class="w-auto">
    <x-modal.header>
        {{ __('auth::profile.activity.details.title') }}
    </x-modal.header>

    <div class="grid md:grid-cols-2 gap-4 p-6">
        <div class="flex flex-col">
            <span class="text-2xl">{{ __('auth::profile.activity.details.old_values') }}</span>
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
            <code>
                @if($oldValues)
                    @foreach($oldValues as $key => $value)
                        @if(!empty($value))
                            {{ $key }}: <span
                                class="{{ array_key_exists($key, $newValues) && !compareValues($value, $newValues[$key]) ? 'bg-red-100 dark:bg-red-900' : '' }}">
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
        <div class="flex flex-col">
            <span class="text-2xl">{{ __('auth::profile.activity.details.new_values') }}</span>
            <x-divider/>
            <code>
                @if($newValues)
                    @foreach($newValues as $key => $value)
                        @if(!empty($value))
                            {{ $key }}: <span
                                class="{{ array_key_exists($key, $oldValues) && !compareValues($value, $oldValues[$key]) ? 'bg-green-100 dark:bg-green-900' : '' }}">
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
