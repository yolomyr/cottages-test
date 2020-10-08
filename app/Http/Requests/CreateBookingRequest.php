<?php

namespace App\Http\Requests;

use App\Rules\BookingDateTimeAvailable;
use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
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
        return [
            'service_id' => 'required|integer|exists:services,id',
            'booking_type_id' => 'required|integer|exists:booking_types,id',
            'booking_date' => ['required', 'date', 'date_format:Y-m-d'],
            'started_at' => ['required', 'date_format:H:i'],
            'finished_at' => ['required', 'date_format:H:i', 'after:started_at', new BookingDateTimeAvailable($this->request->all())],
            'people_number' => ['integer', 'nullable'],
            'commentary' => ['string', 'max:800', 'nullable']
        ];
    }
}
