<?php

namespace App\Http\Requests\Factory;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    public bool $unset_unchanged = false;
    public array $unset_empty_attrs = [];

    abstract public function unsetUnchanged(): void;
    abstract public function unsetEmpty(): void;

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        if (!empty($this->unset_empty_attrs)) {
            $this->unsetEmpty();
        }

        if ($this->unset_unchanged === true) {
            $this->unsetUnchanged();
        }
    }
}
