<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PublishedAtRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if ($value !== null) {
            if (is_bool($value)) {
                return true;
            }


            return strtotime($value) !== false;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must either be a date/time string or boolean.';
    }
}
