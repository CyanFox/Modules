<?php

namespace Modules\Auth\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class Password implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (settings('auth.password.minimum_length')) {
            $rules = [
                'min:'.settings('auth.password.minimum_length'),
            ];

            $messages = ['min' => __('validation.min.string', ['attribute' => $attribute, 'min' => settings('auth.password.minimum_length')])];
        }

        if (settings('auth.password.require.numbers')) {
            $rules[] = 'regex:/[0-9]/';
            $messages['regex'] = __('validation.password.numbers', ['attribute' => $attribute]);
        }

        if (settings('auth.password.require.special_characters')) {
            $rules[] = 'regex:/[^a-zA-Z0-9]/';
            $messages['regex'] = __('validation.password.symbols', ['attribute' => $attribute]);
        }

        if (settings('auth.password.require.uppercase')) {
            $rules[] = 'regex:/[A-Z]/';
            $messages['regex'] = __('validation.password.mixed', ['attribute' => $attribute]);
        }

        if (settings('auth.password.require.lowercase')) {
            $rules[] = 'regex:/[a-z]/';
            $messages['regex'] = __('validation.password.mixed', ['attribute' => $attribute]);
        }

        if (settings('auth.password.blacklist')) {
            $rules[] = 'not_in:'.settings('auth.password.blacklist');
            $messages['not_in'] = __('validation.password.blacklist', ['attribute' => $attribute]);
        }

        if (! isset($rules) || ! isset($messages)) {
            return;
        }

        $validator = Validator::make([$attribute => $value], [$attribute => $rules], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            foreach ($errors[$attribute] as $error) {
                $fail($error);
            }
        }
    }
}
