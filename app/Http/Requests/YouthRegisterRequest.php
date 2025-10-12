<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YouthRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'name' => 'Имя',
            'l_name' => 'Фамилия',
            'f_name' => 'Отчество',
            'address' => 'Адрес',
            'bday' => 'Дата рождения',
            'sex' => 'Пол',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'password' => 'Пароль',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'f_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'bday' => 'nullable|date',
            'sex' => 'nullable|in:male,female',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
