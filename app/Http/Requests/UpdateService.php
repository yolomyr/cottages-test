<?php

namespace App\Http\Requests;

use App\Models\ServiceStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateService extends FormRequest
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
            'id' => 'required|exists:services,id',
            'title' => 'string',
            'status_id' => 'exists:service_statuses,id',
            'logo' => 'image|mimes:jpg,jpeg,png|max:5000',
            'unavailability_reason' => 'string|nullable'
        ];
    }
}
