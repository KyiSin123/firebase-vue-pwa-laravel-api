<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'max: 191'],
            'file' => ['max:2048'],
            'categories.*' => 'required_if:categories.*,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'categories.*.required_if' => 'The categories field is required',
        ];
    }
}
