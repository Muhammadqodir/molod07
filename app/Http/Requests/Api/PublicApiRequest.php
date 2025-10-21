<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PublicApiRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Public API doesn't require authorization
    }

    public function rules()
    {
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'search' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'per_page.max' => 'The per page may not be greater than 100.',
            'per_page.integer' => 'The per page must be an integer.',
            'page.integer' => 'The page must be an integer.',
            'search.max' => 'The search may not be greater than 255 characters.',
            'category.max' => 'The category may not be greater than 100 characters.',
        ];
    }
}
