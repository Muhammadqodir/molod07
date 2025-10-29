<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'user_id' => 'nullable|integer|exists:users,id',

            'category' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Cover is optional for updates

            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',

            'address' => 'nullable|string|max:255',
            'settlement' => 'required|string|max:255',

            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',

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
            'videos.*' => 'file|max:102400',

            'web' => 'nullable|url|max:255',
            'telegram' => 'nullable|string|max:255',
            'vk' => 'nullable|string|max:255',

            'roles' => 'required|string',

            'status' => 'nullable|string|in:pending,approved,rejected,archived',
            'admin_id' => 'nullable|integer|exists:users,id',
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
