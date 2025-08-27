@extends('layouts.sidebar-layout')

@section('title', 'Добавить вакансию')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Добавить вакансию</h1>
    </div>
    <form action="{{ route(Auth::user()->role . '.vacancies.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Работодатель --}}
        <section class="space-y-3">

            @if (Auth::user()->role === 'admin')
                <div class="text-lg font-medium">Работодатель</div>

                <x-input label="ID пользователя" name="user_id" placeholder="ID пользователя" value="{{ old('user_id') }}" />
                <small id="user_name">Введите ID пользователя</small>

                @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const userIdInput = document.querySelector('[name="user_id"]');
                            const userNameElem = document.getElementById('user_name');
                            const orgNameInput = document.querySelector('[name="org_name"]');
                            const orgPhoneInput = document.querySelector('[name="org_phone"]');
                            const orgAddressInput = document.querySelector('[name="org_address"]');
                            const orgEmailInput = document.querySelector('[name="org_email"]');
                            let timeout = null;

                            function setOrgInputs(data, autofilled) {
                                orgNameInput.value = data.name || '';
                                orgPhoneInput.value = data.phone || '';
                                orgAddressInput.value = data.address || '';
                                orgEmailInput.value = data.email || '';
                                orgNameInput.readOnly = autofilled;
                                orgPhoneInput.readOnly = autofilled;
                                orgAddressInput.readOnly = autofilled;
                                orgEmailInput.readOnly = autofilled;

                                // Добавляем визуальные стили для readonly полей
                                if (autofilled) {
                                    orgNameInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                                    orgPhoneInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                                    orgAddressInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                                    orgEmailInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                                } else {
                                    orgNameInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                                    orgPhoneInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                                    orgAddressInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                                    orgEmailInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                                }
                            }

                            function clearOrgInputs() {
                                orgNameInput.value = '';
                                orgPhoneInput.value = '';
                                orgAddressInput.value = '';
                                orgEmailInput.value = '';
                                orgNameInput.readOnly = false;
                                orgPhoneInput.readOnly = false;
                                orgAddressInput.readOnly = false;
                                orgEmailInput.readOnly = false;

                                // Убираем визуальные стили для readonly полей
                                orgNameInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                                orgPhoneInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                                orgAddressInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                                orgEmailInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                            }

                            userIdInput.addEventListener('input', function() {
                                clearTimeout(timeout);
                                const id = userIdInput.value.trim();
                                if (!id) {
                                    userNameElem.textContent = 'Введите ID пользователя';
                                    clearOrgInputs();
                                    return;
                                }
                                timeout = setTimeout(() => {
                                    fetch(`/admin/get-partner/${id}`)
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.error || !data.name) {
                                                userNameElem.textContent = 'Пользователь не найден';
                                                clearOrgInputs();
                                                return;
                                            }
                                            userNameElem.textContent = `${data.name} ${data.l_name || ''}`
                                                .trim();
                                            setOrgInputs(data, true);
                                        })
                                        .catch(() => {
                                            userNameElem.textContent = 'Ошибка поиска пользователя';
                                            clearOrgInputs();
                                        });
                                }, 500);
                            });
                        });
                    </script>
                @endpush

                <x-input label="Название организации" name="org_name" placeholder="ООО Компания"
                    value="{{ old('org_name') }}" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input type="tel" label="Телефон организации" name="org_phone" placeholder="+7(999)999-99-99"
                        value="{{ old('org_phone') }}" />
                    <x-input type="email" label="E‑mail организации" name="org_email" placeholder="hr@company.com"
                        value="{{ old('org_email') }}" />
                </div>

                <x-input label="Адрес организации" name="org_address" placeholder="Укажите полный адрес организации"
                    value="{{ old('org_address') }}" />
                <hr>
            @endif

            <div class="text-lg font-medium">Направление и тип вакансии</div>

            {{-- Категории и типы вакансий --}}
            @php
                $categories = [
                    'it' => 'IT и разработка',
                    'marketing' => 'Маркетинг и реклама',
                    'sales' => 'Продажи',
                    'hr' => 'HR и кадры',
                    'finance' => 'Финансы и бухгалтерия',
                    'design' => 'Дизайн',
                    'management' => 'Менеджмент',
                    'education' => 'Образование',
                    'healthcare' => 'Здравоохранение',
                    'construction' => 'Строительство',
                    'tourism' => 'Туризм и гостеприимство',
                    'retail' => 'Розничная торговля',
                    'logistics' => 'Логистика и транспорт',
                    'manufacturing' => 'Производство',
                    'agriculture' => 'Сельское хозяйство',
                    'other' => 'Другое',
                ];

                $types = [
                    'full_time' => 'Полная занятость',
                    'part_time' => 'Частичная занятость',
                    'remote' => 'Удаленная работа',
                    'freelance' => 'Фриланс',
                    'internship' => 'Стажировка',
                    'contract' => 'Контракт',
                ];

                $experience_levels = [
                    'no_experience' => 'Без опыта',
                    '1_3_years' => '1-3 года',
                    '3_6_years' => '3-6 лет',
                    '6_plus_years' => 'Более 6 лет',
                ];
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category', '')" :multiple="false" title="Категория"
                    hint="Выберите один вариант" />
                @error('category')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-multi-choice name="type" :options="$types" :value="old('type', '')" :multiple="false" title="Тип занятости"
                    hint="Выберите один вариант" />
                @error('type')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-multi-choice name="experience" :options="$experience_levels" :value="old('experience', '')" :multiple="false"
                    title="Требуемый опыт" hint="Выберите один вариант" />
                @error('experience')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <hr>

        {{-- Описание вакансии --}}
        <section class="space-y-4">
            <div class="text-lg font-medium">Описание вакансии</div>

            <x-input label="Название должности" name="title" placeholder="Укажите название должности" maxlength="255"
                value="{{ old('title') }}" />

            <x-rich-text-area label="Описание вакансии" name="description"
                placeholder="Полное описание вакансии, обязанности, требования" value="{{ old('description') }}" />
        </section>

        <hr>

        {{-- Зарплата --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Зарплата</div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input type="number" label="Зарплата от (руб.)" name="salary_from" placeholder="50000"
                    value="{{ old('salary_from') }}" />
                <x-input type="number" label="Зарплата до (руб.)" name="salary_to" placeholder="100000"
                    value="{{ old('salary_to') }}" />
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" id="salary_negotiable" name="salary_negotiable" value="1"
                    {{ old('salary_negotiable') ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <label for="salary_negotiable" class="text-sm text-gray-700">По договоренности</label>
            </div>
        </section>

        <hr>

        {{-- Кнопка --}}
        <div class="pt-2 flex justify-start">
            <x-button type="submit">Отправить на модерацию</x-button>
        </div>

        {{-- Примечание --}}
        <p class="text-xs text-gray-500">
            Нажимая «Отправить», вы соглашаетесь на обработку персональных данных и с правилами пользования платформой.
        </p>
    </form>

@endsection
