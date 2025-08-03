@php
$faqs = [
    [
        'question' => 'Как зарегистрироваться на платформе?',
        'answer' => 'Перейдите на главную страницу и нажмите на кнопку "Регистрация". Заполните необходимые поля и подтвердите свою почту.'
    ],
    [
        'question' => 'Как подать заявку на участие в мероприятии?',
        'answer' => 'Выберите интересующее мероприятие, нажмите "Подробнее" и следуйте инструкциям на странице описания.'
    ],
    [
        'question' => 'Как предложить идею мероприятия?',
        'answer' => 'Перейдите в раздел "О нас" и заполните форму обратной связи с описанием вашей идеи.'
    ],
    [
        'question' => 'Как накапливать баллы?',
        'answer' => 'Баллы начисляются за участие в мероприятиях, прохождение курсов и активность на платформе.'
    ],
    [
        'question' => 'Как и на что можно потратить баллы?',
        'answer' => 'Баллы можно обменять на сертификаты, участие в закрытых мероприятиях или подарки в специальном разделе платформы.'
    ],
];
@endphp

<section class="bg-gray-50 py-16 px-4 sm:px-6 lg:px-16">
    <div class="max-w-screen-xl mx-auto space-y-6">
        <h2 class="text-3xl font-semibold text-gray-800">Часто задаваемые вопросы</h2>

        <div class="space-y-3">
            @foreach($faqs as $index => $faq)
                <div
                    x-data="{ open: false }"
                    class="bg-white rounded-xl px-6 py-4 shadow-sm transition"
                >
                    <button
                        @click="open = !open"
                        class="w-full flex justify-between items-center text-left"
                    >
                        <span class="text-base font-medium text-gray-800">
                            {{ $faq['question'] }}
                        </span>
                        <x-lucide-chevron-down
                            class="w-5 h-5 text-gray-600 transition-transform duration-300"
                            x-bind:class="open ? 'rotate-180' : ''"
                        />
                    </button>

                    <div
                        x-show="open"
                        x-transition
                        x-collapse
                        class="text-gray-600 mt-2 text-sm leading-relaxed"
                    >
                        {{ $faq['answer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>