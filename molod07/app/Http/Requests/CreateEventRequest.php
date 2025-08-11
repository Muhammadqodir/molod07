<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',

            'category' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',

            'address' => 'nullable|string|max:255',
            'settlement' => 'nullable|string|max:255',

            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',

            'supervisor_id' => 'nullable|integer',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_l_name' => 'nullable|string|max:255',
            'supervisor_phone' => 'nullable|string|max:20',
            'supervisor_email' => 'nullable|email|max:255',

            'docs' => 'nullable|array',
            'docs.*' => 'file|max:5120',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',

            'videos' => 'nullable|array',
            'videos.*' => 'url',

            'web' => 'nullable|url|max:255',
            'telegram' => 'nullable|string|max:255',
            'vk' => 'nullable|string|max:255',

            'roles' => 'nullable|array',
            'roles.*' => 'string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'category' => 'категория',
            'type' => 'тип',
            'cover' => 'обложка',
            'title' => 'название',
            'short_description' => 'краткое описание',
            'description' => 'описание',
            'address' => 'адрес',
            'settlement' => 'населённый пункт',
            'start' => 'дата начала',
            'end' => 'дата окончания',
            'supervisor_name' => 'имя руководителя',
            'supervisor_l_name' => 'фамилия руководителя',
            'supervisor_phone' => 'телефон руководителя',
            'supervisor_email' => 'email руководителя',
            'docs' => 'документы',
            'images' => 'изображения',
            'videos' => 'видео',
            'web' => 'веб-сайт',
            'telegram' => 'телеграм',
            'vk' => 'вконтакте',
            'roles' => 'роли',
        ];
    }
}
