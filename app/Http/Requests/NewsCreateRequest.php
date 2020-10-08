<?php

namespace App\Http\Requests;

use App\Http\Requests\Factory\FileRequest;

class NewsCreateRequest extends FileRequest
{
    public array $unset_empty_attrs = ['files'];

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
            'logo' => 'required|image|mimes:png,jpeg,jpg|max:5000',
            'subtitle' => 'required|string|max:250',
            'content' => 'required|string',
            'files' => 'array|min:1',
            'files.*' => 'file|mimes:doc,docx,xls,xlsx,pdf,png,jpg,jpeg|max:20000'
        ];
    }
}
