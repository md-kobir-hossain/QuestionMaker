<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'chapter_id' => ['required', 'exists:chapters,id'],
            'stem' => ['required', 'string'],
            'parts' => ['required', 'array', 'min:1'],
            'parts.*.label' => ['required', 'string', 'max:10'],
            'parts.*.question' => ['required', 'string'],
            'parts.*.marks' => ['required', 'integer', 'min:1'],
        ];
    }
}
