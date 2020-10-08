<?php

namespace App\Rules;

use App\Traits\Caster;
use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    use Caster;
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
     * @param string $attribute
     * @param $phone_number
     * @return bool
     */
    final public function passes($attribute, $phone_number): bool
    {
        if (!is_string($phone_number)) {
            return false;
        }

        $phone_number = self::castPhoneToDbFormat($phone_number);
        return self::checkPhoneFormat($phone_number);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    final public function message(): string
    {
        return 'Номер телефона не соответствует формату, прим. - +7 (111) 111-22-33';
    }
}
