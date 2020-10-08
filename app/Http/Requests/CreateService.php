<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateService extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'status_id' => 'exists:service_statuses,id',
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:5000',
            'unavailability_reason' => 'string|nullable'
        ];
    }
}
