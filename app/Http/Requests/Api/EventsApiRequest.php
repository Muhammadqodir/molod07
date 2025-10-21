<?php

namespace App\Http\Requests\Api;

class EventsApiRequest extends PublicApiRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'type' => 'sometimes|string|max:100',
            'settlement' => 'sometimes|string|max:100',
        ]);
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'type.max' => 'The type may not be greater than 100 characters.',
            'settlement.max' => 'The settlement may not be greater than 100 characters.',
        ]);
    }
}
