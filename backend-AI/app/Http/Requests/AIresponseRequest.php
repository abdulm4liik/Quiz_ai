<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AIresponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->method()){
            'POST' => [
                'pdf' => 'required|file|mimes:pdf', 
                'response_type' => 'required|integer|in:0,1',
            ],
            'PUT', 'PATCH' => [
                'quiz_id' => 'required|integer',
                'marks' => 'required|array',
                'marks.correct_answers' => 'required|array',
                'marks.your_answers' => 'required|array',
                'marks.total' => 'required|integer',
            ],
            default => [],
        };
    }
}
