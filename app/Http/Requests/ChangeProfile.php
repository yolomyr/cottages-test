<?php

namespace App\Http\Requests;

use App\Http\Requests\Factory\UserRequest;
use App\Rules\PhoneNumber;
use App\Traits\Caster;

class ChangeProfile extends UserRequest
{
    use Caster;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    final public function rules(): array
    {
        $phone_number_rule = new PhoneNumber;

        return [
            // main info
            'name' => ['string', 'max:100'],
            'surname' => ['string', 'max:100'],
            'birthday' => ['date', 'before:tomorrow'],
            'gender_id' => ['integer', 'exists:App\Models\Gender,id'],

            //contacts
            'phone' => [$phone_number_rule, 'unique:users'],
            'whatsapp_phone' => [$phone_number_rule, 'nullable'],
            'telegram_phone' => [$phone_number_rule, 'nullable'],
            'viber_phone' => [$phone_number_rule, 'nullable'],

            // other info
            'work_info' => ['string', 'max:1000', 'nullable'],
            'hobby_info' => ['string', 'max:1000', 'nullable'],
            'family_info' => ['string', 'max:1000', 'nullable'],
            'extra_info' => ['string', 'max:1000', 'nullable'],

            // user_estates
            'user_estates' => ['required', 'array', 'min:1'],
            'user_estates.*.id' => ['integer', 'exists:App\Models\UserEstate,id,user_id,' . auth()->user()->id],
            'user_estates.*.estate_type_id' => ['integer', 'exists:App\Models\UserEstateType,id'],
            'user_estates.*.estate_number' => ['required_with:user_estates.*.estate_type_id', 'integer', 'min:1']
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => is_string($this->phone) ? self::castPhoneToDbFormat($this->phone) : $this->phone,
        ]);

        $this->unsetUnchanged();
    }
}
