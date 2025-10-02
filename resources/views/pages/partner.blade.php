@extends('layouts.app')

@section('title', $partner->partnersProfile->org_name)

@section('content')

    <section class="bg-accentbg mt-[-80px] pt-[80px]">
        <div class="max-w-6xl mx-auto py-6 space-y-8" x-data="{ tab: 'info' }">

            {{-- Основная информация о партнере --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-4 md:p-6">
                    {{-- Фото организации --}}
                    <div class="md:col-span-4">
                        <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden">
                            @if ($partner->partnersProfile->pic)
                                <img src="{{ asset('uploads/' . $partner->partnersProfile->pic) }}"
                                     alt="{{ $partner->partnersProfile->org_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-lucide-building-2 class="w-16 h-16 text-gray-400" />
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Основная информация --}}
                    <div class="md:col-span-8 flex flex-col gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                {{ $partner->partnersProfile->org_name }}
                            </h1>

                            @if($partner->partnersProfile->getDirector())
                                <p class="text-lg text-gray-600 mb-2">
                                    Руководитель: {{ $partner->partnersProfile->getDirector() }}
                                </p>
                            @endif
                        </div>

                        {{-- Контактная информация --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if($partner->partnersProfile->org_address)
                                <div class="flex items-start gap-3">
                                    <x-lucide-map-pin class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <div class="text-sm text-gray-500">Адрес</div>
                                        <div class="text-gray-900">{{ $partner->partnersProfile->org_address }}</div>
                                    </div>
                                </div>
                            @endif

                            @if($partner->partnersProfile->phone)
                                <div class="flex items-start gap-3">
                                    <x-lucide-phone class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <div class="text-sm text-gray-500">Телефон</div>
                                        <div class="text-gray-900">
                                            <a href="tel:{{ $partner->partnersProfile->phone }}" class="hover:text-primary">
                                                {{ $partner->partnersProfile->phone }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($partner->partnersProfile->web)
                                <div class="flex items-start gap-3">
                                    <x-lucide-globe class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <div class="text-sm text-gray-500">Веб-сайт</div>
                                        <div class="text-gray-900">
                                            <a href="{{ $partner->partnersProfile->web }}" target="_blank"
                                               class="hover:text-primary break-all">
                                                {{ $partner->partnersProfile->web }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($partner->partnersProfile->telegram || $partner->partnersProfile->vk)
                                <div class="flex items-start gap-3">
                                    <x-lucide-share-2 class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <div class="text-sm text-gray-500">Социальные сети</div>
                                        <div class="flex gap-2">
                                            @if($partner->partnersProfile->telegram)
                                                <a href="{{ $partner->partnersProfile->telegram }}" target="_blank"
                                                   class="text-blue-500 hover:text-blue-600">
                                                    Telegram
                                                </a>
                                            @endif
                                            @if($partner->partnersProfile->vk)
                                                <a href="{{ $partner->partnersProfile->vk }}" target="_blank"
                                                   class="text-blue-500 hover:text-blue-600">
                                                    VK
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Статистика --}}
                        <div class="flex gap-6 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $events->count() }}</div>
                                <div class="text-sm text-gray-500">Мероприятий</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $vacancies->count() }}</div>
                                <div class="text-sm text-gray-500">Вакансий</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Табы: О партнере, Мероприятия, Вакансии --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                {{-- Заголовки табов --}}
                <div class="flex border-b border-gray-100">
                    <button @click="tab = 'info'"
                            :class="tab === 'info' ? 'border-primary text-primary' : 'border-transparent text-gray-500'"
                            class="px-6 py-4 text-sm font-medium border-b-2 hover:text-gray-700">
                        О партнере
                    </button>
                    <button @click="tab = 'events'"
                            :class="tab === 'events' ? 'border-primary text-primary' : 'border-transparent text-gray-500'"
                            class="px-6 py-4 text-sm font-medium border-b-2 hover:text-gray-700">
                        Мероприятия ({{ $events->count() }})
                    </button>
                    <button @click="tab = 'vacancies'"
                            :class="tab === 'vacancies' ? 'border-primary text-primary' : 'border-transparent text-gray-500'"
                            class="px-6 py-4 text-sm font-medium border-b-2 hover:text-gray-700">
                        Вакансии ({{ $vacancies->count() }})
                    </button>
                </div>

                {{-- Содержимое табов --}}
                <div class="p-6">
                    {{-- Таб "О партнере" --}}
                    <div x-show="tab === 'info'" x-transition>
                        @if($partner->partnersProfile->about)
                            <div class="prose prose-gray max-w-none">
                                <p class="text-gray-700 leading-relaxed">
                                    {!! $partner->partnersProfile->about !!}
                                </p>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <x-lucide-info class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                <p class="text-gray-500">Информация о партнере не указана</p>
                            </div>
                        @endif
                    </div>

                    {{-- Таб "Мероприятия" --}}
                    <div x-show="tab === 'events'" x-transition>
                        @if($events->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($events as $event)
                                    <x-event-card
                                        image="{{ asset($event->cover) }}"
                                        :tags="[$event->category, $event->type]"
                                        points="{{ $event->getPoints() }}"
                                        title="{{ $event->title }}"
                                        location="{{ $event->getAddress() }}"
                                        date="{{ $event->start }}"
                                        link="{{ route('event', $event->id) }}" />
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <x-lucide-calendar class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                <p class="text-gray-500">У партнера пока нет опубликованных мероприятий</p>
                            </div>
                        @endif
                    </div>

                    {{-- Таб "Вакансии" --}}
                    <div x-show="tab === 'vacancies'" x-transition>
                        @if($vacancies->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($vacancies as $vacancy)
                                    <x-vacancy-card
                                        title="{{ $vacancy->title }}"
                                        category="{{ $vacancy->category }}"
                                        date="{{ $vacancy->created_at->format('d.m.y') }}"
                                        salary="{{ $vacancy->getSalaryRange() }}"
                                        location="{{ $vacancy->org_address }}"
                                        link="{{ route('vacancy', $vacancy->id) }}" />
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <x-lucide-briefcase class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                <p class="text-gray-500">У партнера пока нет опубликованных вакансий</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
