<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateAdministratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user && $user->role === 'admin';
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'permissions' => json_decode($this->input('permissions', '[]'), true) ?: [],
        ]);
    }

    public function rules(): array
    {
        return [
            // Фото профиля
            'pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // Личные данные
            'name'   => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'f_name' => 'nullable|string|max:255',
            // Контактные данные
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'permissions' => 'required|array|min:1',
            // Пароль
            'password' => 'required|string|min:8',
        ];
    }

    public function attributes(): array
    {
        return [
            'pic'                   => 'фото профиля',
            'name'                  => 'имя',
            'l_name'                => 'фамилия',
            'f_name'                => 'отчество',
            'phone'                 => 'номер телефона',
            'email'                 => 'электронная почта',
            'permissions'           => 'привелегии',
            'password'              => 'пароль',
        ];
    }
}
