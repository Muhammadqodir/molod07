@extends('layouts.sidebar-layout')

@section('title', 'Мои баллы')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Мои баллы</h1>

        <!-- Общая статистика баллов -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 p-6 rounded-xl border border-green-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Заработано</p>
                        <p class="text-2xl font-bold text-green-900">{{ $totalEarned }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 p-6 rounded-xl border border-red-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-600">Потрачено</p>
                        <p class="text-2xl font-bold text-red-900">{{ $totalSpent }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600">Текущий баланс</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $currentBalance }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Вкладки для фильтрации -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm active" data-tab="all">
                    Все операции
                </button>
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="earned">
                    Заработанные <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full ml-2">{{ $earnedPoints->count() }}</span>
                </button>
                <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" data-tab="spent">
                    Потраченные <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full ml-2">{{ $spentPoints->count() }}</span>
                </button>
            </nav>
        </div>

        <!-- Список всех операций -->
        <div id="all-tab" class="tab-content">
            <div class="bg-white overflow-hidden">
                <div class="space-y-4">
                    @forelse($points as $point)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($point->points > 0)
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($point->event)
                                                Участие в мероприятии: {{ $point->event->title }}
                                            @elseif($point->partner)
                                                {{ $point->points > 0 ? 'Начисление от партнёра' : 'Трата у партнёра' }}: {{ $point->partner->getFullName() }}
                                            @else
                                                {{ $point->points > 0 ? 'Начисление баллов' : 'Списание баллов' }}
                                            @endif
                                        </p>
                                        @if($point->extra)
                                            <p class="text-sm text-gray-500">{{ $point->extra }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $point->created_at->format('d.m.Y в H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-semibold {{ $point->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $point->points > 0 ? '+' : '' }}{{ $point->points }}
                                    </span>
                                    <p class="text-xs text-gray-500">баллов</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Нет операций с баллами</h3>
                            <p class="mt-1 text-sm text-gray-500">Принимайте участие в мероприятиях для получения баллов!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Список заработанных баллов -->
        <div id="earned-tab" class="tab-content hidden">
            <div class="bg-white overflow-hidden">
                <div class="space-y-4">
                    @forelse($earnedPoints as $point)
                        <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($point->event)
                                                Участие в мероприятии: {{ $point->event->title }}
                                            @elseif($point->partner)
                                                Начисление от партнёра: {{ $point->partner->getFullName() }}
                                            @else
                                                Начисление баллов
                                            @endif
                                        </p>
                                        @if($point->extra)
                                            <p class="text-sm text-gray-500">{{ $point->extra }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $point->created_at->format('d.m.Y в H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-semibold text-green-600">+{{ $point->points }}</span>
                                    <p class="text-xs text-gray-500">баллов</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Нет заработанных баллов</h3>
                            <p class="mt-1 text-sm text-gray-500">Принимайте участие в мероприятиях для получения баллов!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Список потраченных баллов -->
        <div id="spent-tab" class="tab-content hidden">
            <div class="bg-white overflow-hidden">
                <div class="space-y-4">
                    @forelse($spentPoints as $point)
                        <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($point->partner)
                                                Трата у партнёра: {{ $point->partner->getFullName() }}
                                            @else
                                                Списание баллов
                                            @endif
                                        </p>
                                        @if($point->extra)
                                            <p class="text-sm text-gray-500">{{ $point->extra }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $point->created_at->format('d.m.Y в H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-semibold text-red-600">{{ $point->points }}</span>
                                    <p class="text-xs text-gray-500">баллов</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Нет потраченных баллов</h3>
                            <p class="mt-1 text-sm text-gray-500">Вы ещё не тратили баллы.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Убираем активный класс у всех кнопок
                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-blue-500', 'text-blue-600', 'active');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });

                    // Добавляем активный класс к текущей кнопке
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('border-blue-500', 'text-blue-600', 'active');

                    // Скрываем все контенты
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Показываем нужный контент
                    document.getElementById(tabId + '-tab').classList.remove('hidden');
                });
            });
        });
    </script>
@endsection
