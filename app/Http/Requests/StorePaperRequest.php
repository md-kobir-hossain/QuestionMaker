<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'school_name' => ['required', 'string', 'max:255'],
            'exam_name' => ['required', 'string', 'max:255'],
            'exam_time' => ['required', 'string', 'max:50'],
            'full_marks' => ['required', 'integer', 'min:1'],
            'mcq_ids' => ['nullable', 'array'],
            'mcq_ids.*' => ['integer', 'exists:mcqs,id'],
            'short_ids' => ['nullable', 'array'],
            'short_ids.*' => ['integer', 'exists:short_questions,id'],
            'cq_ids' => ['nullable', 'array'],
            'cq_ids.*' => ['integer', 'exists:cqs,id'],
            'include_answer_key' => ['boolean'],
        ];
    }
}
