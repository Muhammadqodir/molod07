<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePodcastRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'category' => 'required|string|max:255',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Cover is optional for updates
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'length' => 'required|string|max:20',
            'episode_numbers' => 'required|integer|min:1',
            'link' => 'required|url|max:500',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Пожалуйста, выберите автора подкаста.',
            'user_id.exists' => 'Выбранный пользователь не существует.',
            'category.required' => 'Пожалуйста, укажите категорию подкаста.',
            'category.max' => 'Категория не должна превышать 255 символов.',
            'cover.image' => 'Обложка должна быть изображением.',
            'cover.mimes' => 'Обложка должна быть в формате JPG, JPEG или PNG.',
            'cover.max' => 'Размер обложки не должен превышать 2 МБ.',
            'title.required' => 'Пожалуйста, введите название подкаста.',
            'title.max' => 'Название не должно превышать 255 символов.',
            'short_description.required' => 'Пожалуйста, введите краткое описание.',
            'short_description.max' => 'Краткое описание не должно превышать 500 символов.',
            'length.required' => 'Пожалуйста, укажите продолжительность подкаста.',
            'length.max' => 'Продолжительность не должна превышать 20 символов.',
            'episode_numbers.required' => 'Пожалуйста, укажите номер эпизода.',
            'episode_numbers.integer' => 'Номер эпизода должен быть числом.',
            'episode_numbers.min' => 'Номер эпизода должен быть больше 0.',
            'link.required' => 'Пожалуйста, введите ссылку на подкаст.',
            'link.url' => 'Ссылка должна быть корректным URL.',
            'link.max' => 'Ссылка не должна превышать 500 символов.',
        ];
    }
}
