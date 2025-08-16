<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'org_name' => ['required', 'string', 'max:255'],
            'person_name' => ['required', 'string', 'max:255'],
            'person_lname' => ['required', 'string', 'max:255'],
            'org_address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/'
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'org_name' => 'название организации',
            'person_name' => 'имя контактного лица',
            'person_lname' => 'фамилия контактного лица',
            'org_address' => 'населенный пункт',
            'email' => 'электронная почта',
            'phone' => 'телефон',
            'password' => 'пароль',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'phone.regex' => 'Телефон должен быть в формате +7(999)999-99-99.',
            'password.regex' => 'Пароль должен содержать и латиницу, и цифры.',
        ];
    }
}
