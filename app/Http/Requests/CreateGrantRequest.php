<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateGrantRequest extends FormRequest
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
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || $user->role !== 'partner') {
                        $fail('Выбранный пользователь должен быть партнёром.');
                    }
                },
            ],

            'category' => 'required|string|max:255',
            'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',

            'conditions' => 'required|string',
            'requirements' => 'required|string',
            'reward' => 'required|string',

            'settlement' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'deadline' => 'required|date|after:today',

            'docs' => 'nullable|array|max:5',
            'docs.*' => 'file|mimes:pdf|max:5120',

            'web' => 'nullable|url|max:255',
            'telegram' => 'nullable|string|max:255',
            'vk' => 'nullable|string|max:255',

            'status' => 'nullable|string|in:pending,approved,rejected',
            'admin_id' => 'nullable|integer|exists:users,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'ID партнера',
            'category' => 'категория',
            'cover' => 'обложка',
            'title' => 'название',
            'short_description' => 'краткое описание',
            'description' => 'описание',
            'conditions' => 'условия участия',
            'requirements' => 'требования',
            'reward' => 'награда/поощрение',
            'settlement' => 'населённый пункт',
            'address' => 'адрес',
            'deadline' => 'дедлайн подачи заявок',
            'docs' => 'документы',
            'web' => 'веб-сайт',
            'telegram' => 'телеграм',
            'vk' => 'вконтакте',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Необходимо указать ID партнера.',
            'user_id.exists' => 'Партнер с указанным ID не найден.',
            'category.required' => 'Необходимо выбрать категорию гранта.',
            'category.in' => 'Выбранная категория недопустима.',
            'cover.required' => 'Необходимо загрузить обложку.',
            'cover.image' => 'Обложка должна быть изображением.',
            'cover.mimes' => 'Обложка должна быть в формате JPG, JPEG или PNG.',
            'cover.max' => 'Размер обложки не должен превышать 2 МБ.',
            'title.required' => 'Необходимо указать название гранта.',
            'title.max' => 'Название не должно превышать 255 символов.',
            'short_description.required' => 'Необходимо указать краткое описание.',
            'short_description.max' => 'Краткое описание не должно превышать 500 символов.',
            'description.required' => 'Необходимо указать полное описание гранта.',
            'conditions.required' => 'Необходимо указать условия участия.',
            'requirements.required' => 'Необходимо указать требования к участникам.',
            'reward.required' => 'Необходимо указать награду или поощрение.',
            'settlement.required' => 'Необходимо указать населённый пункт.',
            'settlement.max' => 'Название населённого пункта не должно превышать 255 символов.',
            'address.max' => 'Адрес не должен превышать 255 символов.',
            'deadline.required' => 'Необходимо указать дедлайн подачи заявок.',
            'deadline.date' => 'Дедлайн должен быть корректной датой.',
            'deadline.after' => 'Дедлайн должен быть позже сегодняшней даты.',
            'docs.max' => 'Можно загрузить максимум 5 документов.',
            'docs.*.file' => 'Каждый документ должен быть файлом.',
            'docs.*.mimes' => 'Документы должны быть в формате PDF.',
            'docs.*.max' => 'Размер документа не должен превышать 5 МБ.',
            'images.max' => 'Можно загрузить максимум 6 изображений.',
            'images.*.image' => 'Каждый файл должен быть изображением.',
            'images.*.mimes' => 'Изображения должны быть в формате JPG, JPEG или PNG.',
            'images.*.max' => 'Размер изображения не должен превышать 2 МБ.',
            'videos.max' => 'Можно загрузить максимум 1 видео.',
            'videos.*.file' => 'Видео должно быть файлом.',
            'videos.*.mimes' => 'Видео должно быть в формате MP4 или MOV.',
            'videos.*.max' => 'Размер видео не должен превышать 100 МБ.',
            'web.url' => 'Ссылка на сайт должна быть корректным URL.',
            'web.max' => 'Ссылка на сайт не должна превышать 255 символов.',
            'telegram.max' => 'Telegram не должен превышать 255 символов.',
            'vk.max' => 'VK не должен превышать 255 символов.',
        ];
    }
}
