<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGrantRequest extends FormRequest
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
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Cover is optional for updates

            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',

            'conditions' => 'required|string',
            'requirements' => 'required|string',
            'reward' => 'required|string',

            'settlement' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'deadline' => 'required|date|after:today',

            'docs' => 'nullable|array',
            'docs.*' => 'file|max:5120', // 5MB max per document

            'web' => 'nullable|url|max:255',
            'telegram' => 'nullable|string|max:255',
            'vk' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'Выбранный партнер не существует',
            'category.required' => 'Поле категория обязательно для заполнения',
            'cover.image' => 'Обложка должна быть изображением',
            'cover.max' => 'Размер обложки не должен превышать 2 МБ',
            'title.required' => 'Поле название обязательно для заполнения',
            'title.max' => 'Название не должно превышать 255 символов',
            'short_description.required' => 'Поле короткое описание обязательно для заполнения',
            'short_description.max' => 'Короткое описание не должно превышать 500 символов',
            'description.required' => 'Поле описание обязательно для заполнения',
            'conditions.required' => 'Поле условия участия обязательно для заполнения',
            'requirements.required' => 'Поле требования обязательно для заполнения',
            'reward.required' => 'Поле награда/поощрение обязательно для заполнения',
            'settlement.required' => 'Поле населённый пункт обязательно для заполнения',
            'settlement.max' => 'Населённый пункт не должен превышать 255 символов',
            'address.max' => 'Адрес не должен превышать 255 символов',
            'deadline.required' => 'Поле дедлайн подачи заявок обязательно для заполнения',
            'deadline.date' => 'Дедлайн должен быть датой',
            'deadline.after' => 'Дедлайн должен быть больше сегодняшней даты',
            'docs.array' => 'Документы должны быть массивом файлов',
            'docs.*.file' => 'Каждый документ должен быть файлом',
            'docs.*.max' => 'Размер каждого документа не должен превышать 5 МБ',
            'web.url' => 'Сайт должен быть корректной ссылкой',
            'web.max' => 'Ссылка на сайт не должна превышать 255 символов',
            'telegram.max' => 'Telegram не должен превышать 255 символов',
            'vk.max' => 'VKontakte не должен превышать 255 символов',
        ];
    }
}
