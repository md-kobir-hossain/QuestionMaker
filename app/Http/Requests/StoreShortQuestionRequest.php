<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShortQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'chapter_id' => ['required', 'exists:chapters,id'],
            'question' => ['required', 'string'],
            'marks' => ['required', 'integer', 'min:1'],
        ];
    }
}
