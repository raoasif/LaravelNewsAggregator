<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPreferencesRequest extends FormRequest
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
        return [
            'categories' => 'array|required_without_all:sources,authors',
            'categories.*' => 'string',
            'sources' => 'array|required_without_all:categories,authors',
            'sources.*' => 'string',
            'authors' => 'array|required_without_all:categories,sources',
            'authors.*' => 'string',
        ];
    }
}
