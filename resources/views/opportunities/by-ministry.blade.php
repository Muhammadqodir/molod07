@extends('layouts.app')

@section('title', 'Возможности от ' . $ministry->title)

@section('content')

<section class="bg-accentbg mt-[-80px] pt-[80px] min-h-[calc(100vh-200px)] mb-12">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="mt-12">
            <!-- Breadcrumbs -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('main') }}" class="hover:text-blue-600 transition">Главная</a></li>
                    <li><x-lucide-chevron-right class="h-4 w-4" /></li>
                    <li><a href="{{ route('opportunities.index') }}" class="hover:text-blue-600 transition">Возможности</a></li>
                    <li><x-lucide-chevron-right class="h-4 w-4" /></li>
                    <li class="text-gray-800 font-medium">{{ $ministry->title }}</li>
                </ol>
            </nav>

            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-3xl font-semibold text-gray-800 mb-2">Возможности от {{ $ministry->title }}</h2>
                    @if($ministry->description)
                        <p class="text-lg text-gray-600">{{ $ministry->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Filter -->
            <div class="mb-8 text-sm text-gray-600">
                Найдено {{ $opportunities->total() }} {{
                    $opportunities->total() === 1 ? 'возможность' :
                    ($opportunities->total() >= 2 && $opportunities->total() <= 4 ? 'возможности' : 'возможностей')
                }}
            </div>

            @if ($opportunities->isEmpty())
                <x-empty class="w-full" title="Возможности от этого министерства пока не добавлены." />
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($opportunities as $opportunity)
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            @if($opportunity->cover_image)
                                <div class="h-48 bg-gray-200 overflow-hidden">
                                    <img src="{{ asset($opportunity->cover_image) }}"
                                         alt="{{ $opportunity->program_name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="mb-4">
                                    <h4 class="text-xl font-semibold text-gray-800 mb-3 line-clamp-2">
                                        {{ $opportunity->program_name }}
                                    </h4>
                                    <div class="text-gray-600 line-clamp-3 mb-4">
                                        {{ Str::limit($opportunity->participation_conditions, 150) }}
                                    </div>
                                </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex items-start gap-2">
                                    <x-lucide-calendar class="h-4 w-4 text-gray-500 mt-1 flex-shrink-0" />
                                    <div class="text-sm text-gray-600 line-clamp-2">
                                        {{ Str::limit($opportunity->implementation_period, 80) }}
                                    </div>
                                </div>

                                @if($opportunity->responsible_person && isset($opportunity->responsible_person['name']))
                                    <div class="flex items-start gap-2">
                                        <x-lucide-user class="h-4 w-4 text-gray-500 mt-1 flex-shrink-0" />
                                        <div class="text-sm text-gray-600">
                                            <div class="font-medium">{{ $opportunity->responsible_person['name'] }}</div>
                                            <div>{{ $opportunity->responsible_person['position'] ?? '' }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                                <div class="border-t pt-4">
                                    <a href="{{ route('opportunities.show', $opportunity) }}"
                                       class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                        Подробнее
                                        <x-lucide-arrow-right class="h-4 w-4" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($opportunities->hasPages())
                    <div class="mt-12">
                        {{ $opportunities->links() }}
                    </div>
                @endif
            @endif

            <!-- Back to all opportunities -->
            <div class="mt-8 text-center">
                <a href="{{ route('opportunities.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    <x-lucide-arrow-left class="h-4 w-4" />
                    Все возможности
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
