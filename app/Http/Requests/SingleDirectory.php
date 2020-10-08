<?php

namespace App\Http\Requests;

use App\Interfaces\iDirectory;
use App\Rules\InArrayKeys;
use Illuminate\Foundation\Http\FormRequest;

class SingleDirectory extends FormRequest implements iDirectory
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    final public function rules(): array
    {
        return [
            'directory_name' => ['required', 'string', new InArrayKeys(self::DIRECTORIES)]
        ];
    }
}
