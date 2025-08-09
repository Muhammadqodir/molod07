<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateYouthProfileRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'f_name' => 'nullable|string|max:255',
            'bday' => 'required|date',
            'sex' => 'required|in:male,female',

            // Контактные данные
            'phone' => 'required|string|max:20',
            'telegram' => 'nullable|string|max:255',
            'vk' => 'nullable|string|max:255',

            // Региональные данные
            'address' => 'required|string|max:255',
            'citizenship' => 'nullable|string|max:255',

            // О себе
            'about' => 'required|string|max:10000',

            // Фото профиля (если передается)
            'pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            // Личные данные
            'name' => 'имя',
            'l_name' => 'фамилия',
            'f_name' => 'отчество',
            'bday' => 'дата рождения',
            'sex' => 'пол',

            // Контактные данные
            'phone' => 'номер телефона',
            'telegram' => 'Telegram',
            'vk' => 'VKontakte',

            // Региональные данные
            'address' => 'место проживания',
            'citizenship' => 'гражданство',

            // О себе
            'about' => 'описание о себе',

            // Фото
            'pic' => 'фото профиля',
        ];
    }
}
