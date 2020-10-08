<?php

namespace App\Casts;

use App\Traits\Caster;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber implements CastsAttributes
{
    use Caster;

    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  string  $phone_number
     * @param  array  $attributes
     * @return string
     */
    final public function get($model, $key, $phone_number, $attributes): string
    {
        return is_string($phone_number) ? self::castPhoneToHuman($phone_number) : (string) $phone_number;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param string $phone_number
     * @param array $attributes
     * @return string
     */
    final public function set($model, $key, $phone_number, $attributes): string
    {
        return is_string($phone_number) ? self::castPhoneToDbFormat($phone_number) : (string) $phone_number;
    }
}
