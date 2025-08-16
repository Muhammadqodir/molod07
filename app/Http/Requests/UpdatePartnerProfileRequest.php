<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartnerProfileRequest extends FormRequest
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
            // Личные данные
            'org_name' => 'required|string|max:255',
            'person_name' => 'required|string|max:255',
            'person_lname' => 'nullable|string|max:255',
            'org_address' => 'required|string|max:255',
            'telegram' => 'nullable|string|max:255',
            'vk' => 'nullable|string|max:255',
            'about' => 'nullable|string|max:5000',
            // Контактные данные
            'phone' => 'required|string|max:20',
            'web' => 'nullable|url|max:255',
            // E-mail не редактируется, поэтому не валидируется
            // Фото профиля (если передается)
            'pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'org_name' => 'Название организации',
            'person_name' => 'Имя контактного лица',
            'person_lname' => 'Фамилия контактного лица',
            'org_address' => 'Адрес организации',
            'telegram' => 'Telegram',
            'vk' => 'ВКонтакте',
            'about' => 'Описание',
            'phone' => 'Телефон',
            'web' => 'Веб-сайт',
            'pic' => 'Фото профиля',
        ];
    }
}
