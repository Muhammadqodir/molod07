<?php

namespace App\Http\Requests\Api;

class VacanciesApiRequest extends PublicApiRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'type' => 'sometimes|string|max:100',
            'experience' => 'sometimes|string|max:100',
            'salary_from' => 'sometimes|integer|min:0',
            'salary_to' => 'sometimes|integer|min:0',
        ]);
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'type.max' => 'The type may not be greater than 100 characters.',
            'experience.max' => 'The experience may not be greater than 100 characters.',
            'salary_from.integer' => 'The salary from must be an integer.',
            'salary_from.min' => 'The salary from must be at least 0.',
            'salary_to.integer' => 'The salary to must be an integer.',
            'salary_to.min' => 'The salary to must be at least 0.',
        ]);
    }
}
