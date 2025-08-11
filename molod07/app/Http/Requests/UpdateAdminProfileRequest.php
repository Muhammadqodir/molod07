<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminProfileRequest extends FormRequest
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
            // Контактные данные
            'phone' => 'required|string|max:20',
            // E-mail не редактируется, поэтому не валидируется
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
            // Контактные данные
            'phone' => 'номер телефона',
            // Фото
            'pic' => 'фото профиля',
        ];
    }
}
