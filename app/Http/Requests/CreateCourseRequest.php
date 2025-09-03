<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
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
            'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'length' => 'required|string|max:20',
            'module_count' => 'required|integer|min:1',
            'link' => 'required|url|max:500',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Пожалуйста, выберите автора курса.',
            'user_id.exists' => 'Выбранный пользователь не существует.',
            'category.required' => 'Пожалуйста, укажите категорию курса.',
            'category.max' => 'Категория не должна превышать 255 символов.',
            'cover.required' => 'Пожалуйста, загрузите обложку для курса.',
            'cover.image' => 'Обложка должна быть изображением.',
            'cover.mimes' => 'Обложка должна быть в формате JPG, JPEG или PNG.',
            'cover.max' => 'Размер обложки не должен превышать 2 МБ.',
            'title.required' => 'Пожалуйста, введите название курса.',
            'title.max' => 'Название не должно превышать 255 символов.',
            'short_description.required' => 'Пожалуйста, введите краткое описание курса.',
            'short_description.max' => 'Краткое описание не должно превышать 500 символов.',
            'description.required' => 'Пожалуйста, введите описание курса.',
            'length.required' => 'Пожалуйста, укажите длительность курса.',
            'length.max' => 'Длительность не должна превышать 20 символов.',
            'module_count.required' => 'Пожалуйста, укажите количество модулей.',
            'module_count.integer' => 'Количество модулей должно быть числом.',
            'module_count.min' => 'Количество модулей должно быть не менее 1.',
            'link.required' => 'Пожалуйста, введите ссылку на курс.',
            'link.url' => 'Ссылка должна быть действительным URL.',
            'link.max' => 'Ссылка не должна превышать 500 символов.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'автор',
            'category' => 'категория',
            'cover' => 'обложка',
            'title' => 'название',
            'short_description' => 'краткое описание',
            'description' => 'описание',
            'length' => 'длительность',
            'module_count' => 'количество модулей',
            'link' => 'ссылка',
        ];
    }
}
