<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AuthFieldEquals implements Rule
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
    final public function passes($attribute, $value): bool
    {
        $user = auth()->user();
        return $user !== null ? $user->{$attribute} === $value : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    final public function message(): string
    {
        return 'User object does not contain requested field';
    }
}
