<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InArrayKeys implements Rule
{
    // input array
    public array $array;

    /**
     * Create a new rule instance.
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
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
        return is_string($value) && isset($this->array[$value]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    final public function message(): string
    {
        return 'Не найден ключ для данного массива.';
    }
}
