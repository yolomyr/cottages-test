<?php

namespace App\Http\Requests;

use App\Rules\AuthFieldEquals;
use Illuminate\Foundation\Http\FormRequest;

class ChangeEmail extends FormRequest
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
            'email' => ['required', 'email', new AuthFieldEquals],
            'new_email' => ['required', 'email', 'unique:users,email', 'different:email']
        ];
    }
}
