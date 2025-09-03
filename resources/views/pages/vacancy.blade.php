@extends('layouts.app')

@section('title', $vacancy->title)

@section('content')

    <section class="bg-accentbg mt-[-80px] pt-[80px] min-h-[calc(100vh-200px)]">
        {{-- Flash messages as alerts --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                class="fixed z-[9999999999] top-[80px] right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <div class="flex items-center gap-2">
                    <x-lucide-check-circle class="w-5 h-5" />
                    <strong>Успешно!</strong>
                </div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                class="fixed z-[9999999999] top-[80px] right-6 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <div class="flex items-center gap-2">
                    <x-lucide-alert-circle class="w-5 h-5" />
                    <strong>Ошибка!</strong>
                </div>
                <div class="text-sm mt-1">{{ session('error') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                class="fixed z-[9999999999] top-[80px] right-6 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <strong>Ошибка!</strong>
                <ul class="text-sm mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-6xl mx-auto py-6 space-y-8" x-data="{ tab: 'info', roleIdx: 0, showVacancyResponseDialog: false }">

            {{-- Top: cover + header --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 md:p-6">

                    {{-- Main info --}}
                    <div class="md:col-span-8 flex flex-col gap-3">
                        {{-- Верхняя панель: Назад / Закрыть --}}
                        <div class="flex items-center justify-between p-5">
                            <a href="{{ url()->previous() }}"
                                class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
                                <x-lucide-arrow-left class="w-5 h-5" />
                                <span>Назад</span>
                            </a>
                        </div>

                        <div class="px-6 pb-8 md:px-10 md:pb-12">
                            {{-- Заголовок --}}
                            <h1 class="text-[28px] md:text-[34px] font-semibold text-gray-900 leading-tight">
                                {{ $vacancy->title }}
                            </h1>

                            {{-- Чип + дата --}}
                            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                                @if ($vacancy->category)
                                    <span class="inline-flex items-center rounded-md bg-[#E8EFFD] text-[#1F3AA9] px-3 py-1">
                                        #{{ $vacancy->category }}
                                    </span>
                                @endif
                                @if ($vacancy->published_at)
                                    <span class="text-gray-500">
                                        Опубликовано {{ $vacancy->published_at->format('d.m.y') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Информация --}}
                            <div class="mt-6 space-y-2 text-[15px] leading-6">
                                <div>
                                    <span class="text-gray-600">Уровень оплаты</span>
                                    <span class="font-medium"> {{ $vacancy->getSalaryRange() }}</span>
                                </div>

                                @if ($vacancy->org_address)
                                    <div>
                                        <span class="text-gray-600">Адрес</span>
                                        <span class="font-medium">{{ $vacancy->org_address }}</span>
                                    </div>
                                @endif

                                @if ($vacancy->type)
                                    <div>
                                        <span class="text-gray-600">Тип занятости</span>
                                        <span class="font-medium">{{ $vacancy->type }}</span>
                                    </div>
                                @endif

                                @if ($vacancy->experience)
                                    <div>
                                        <span class="text-gray-600">Требуемый опыт</span>
                                        <span class="font-medium">{{ $vacancy->experience }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Описание --}}
                            @if ($vacancy->description)
                                <div class="mt-8 space-y-2">
                                    <div class="text-[17px] font-medium text-gray-900">Описание</div>
                                    <div class="text-[15px] leading-7 text-gray-800">
                                        {!! $vacancy->description !!}
                                    </div>
                                </div>
                            @endif

                            {{-- Контакты --}}
                            <div class="mt-8 space-y-3">
                                <div class="text-[17px] font-medium text-gray-900">Контакты компании</div>
                                <div class="space-y-1 text-[15px] leading-7">
                                    @if ($vacancy->org_name)
                                        <div class="flex items-center gap-2">
                                            <x-lucide-building class="w-5 h-5 text-gray-400" />
                                            <span>{{ $vacancy->org_name }}</span>
                                        </div>
                                    @endif
                                    @if ($vacancy->org_address)
                                        <div class="flex items-center gap-2">
                                            <x-lucide-map-pin class="w-5 h-5 text-gray-400" />
                                            <span>{{ $vacancy->org_address }}</span>
                                        </div>
                                    @endif
                                    @if ($vacancy->org_phone)
                                        <div class="flex items-center gap-2">
                                            <x-lucide-phone class="w-5 h-5 text-gray-400" />
                                            <span>{{ $vacancy->org_phone }}</span>
                                        </div>
                                    @endif
                                    @if ($vacancy->org_email)
                                        <div class="flex items-center gap-2 text-gray-700">
                                            <x-lucide-mail class="w-5 h-5 text-gray-400" />
                                            <span>{{ $vacancy->org_email }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Кнопка --}}
                            <div class="mt-8">
                                <button @click="showVacancyResponseDialog = true"
                                    class="inline-flex items-center justify-center rounded-xl bg-[#1F3AA9] px-5 py-3
                                   text-white font-medium hover:bg-[#18308e] transition-colors">
                                    Откликнуться
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Vacancy Response Dialog --}}
            @include('inc.vacancy_response', ['vacancy' => $vacancy])
        </div>
    </section>
@endsection
