<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:1000',
            'description' => 'required|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Cover is optional for updates
            'category' => 'required|string|max:255',
            'publication_date' => 'nullable|date',
            'admin_id' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|string|in:draft,published,archived',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'пользователь',
            'title' => 'заголовок',
            'short_description' => 'краткое описание',
            'description' => 'описание',
            'cover' => 'обложка',
            'category' => 'категория',
            'publication_date' => 'дата публикации',
            'admin_id' => 'администратор',
            'status' => 'статус',
        ];
    }
}
