<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVacanciesRequest extends FormRequest
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
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary_from' => 'nullable|numeric|min:0',
            'salary_to' => 'nullable|numeric|min:0|gte:salary_from',
            'salary_negotiable' => 'boolean',
            'type' => 'required|string',
            'experience' => 'required|string',
            'org_name' => 'required|string|max:255',
            'org_phone' => 'required|string|max:20|regex:/^[+]?[0-9\s\-\(\)]+$/',
            'org_email' => 'required|email|max:255',
            'org_address' => 'required|string|max:500',
        ];
    }

    /**
     * Получить пользовательские сообщения об ошибках для правил валидации.
     */
    public function messages(): array
    {
        return [
            'category.required' => 'Поле категории обязательно для заполнения.',
            'title.required' => 'Поле названия вакансии обязательно для заполнения.',
            'description.required' => 'Поле описания вакансии обязательно для заполнения.',
            'salary_to.gte' => 'Максимальная зарплата должна быть больше или равна минимальной зарплате.',
            'type.required' => 'Поле типа занятости обязательно для заполнения.',
            'type.in' => 'Тип занятости должен быть одним из: полный рабочий день, неполный рабочий день, контракт, фриланс, стажировка.',
            'experience.required' => 'Поле уровня опыта обязательно для заполнения.',
            'experience.in' => 'Уровень опыта должен быть одним из: начальный, младший, средний, старший, руководитель.',
            'org_name.required' => 'Поле названия организации обязательно для заполнения.',
            'org_phone.required' => 'Поле телефона организации обязательно для заполнения.',
            'org_phone.regex' => 'Неверный формат телефона организации.',
            'org_email.required' => 'Поле электронной почты организации обязательно для заполнения.',
            'org_email.email' => 'Электронная почта организации должна быть действительным адресом.',
            'org_address.required' => 'Поле адреса организации обязательно для заполнения.',
        ];
    }

    /**
     * Получить пользовательские имена атрибутов для ошибок валидации.
     */
    public function attributes(): array
    {
        return [
            'org_name' => 'название организации',
            'org_phone' => 'телефон организации',
            'org_email' => 'электронная почта организации',
            'org_address' => 'адрес организации',
            'salary_from' => 'минимальная зарплата',
            'salary_to' => 'максимальная зарплата',
            'salary_negotiable' => 'зарплата обсуждается',
        ];
    }


    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert salary_negotiable to boolean if it's a string
        if ($this->has('salary_negotiable')) {
            $this->merge([
                'salary_negotiable' => filter_var($this->salary_negotiable, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        // If salary is negotiable, we might want to allow null values for salary_from and salary_to
        if ($this->salary_negotiable) {
            $this->merge([
                'salary_from' => $this->salary_from ?: null,
                'salary_to' => $this->salary_to ?: null,
            ]);
        }
    }
}
