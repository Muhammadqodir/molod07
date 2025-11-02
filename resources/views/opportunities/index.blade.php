@extends('layouts.app')

@section('title', 'Твои возможности')

@section('content')

<section class="bg-accentbg mt-[-80px] pt-[80px] min-h-[calc(100vh-200px)] mb-12">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="flex justify-between items-center mb-4 mt-12">
            <h2 class="text-3xl font-semibold text-gray-800">Твои возможности</h2>
        </div>

        <div class="mb-8">
            <p class="text-lg text-gray-600">
                Здесь вы найдете все программы и возможности, предоставляемые различными министерствами для молодежи.
            </p>
        </div>

        @if ($ministries->isEmpty())
            <x-empty class="w-full" title="Возможности пока не добавлены." />
        @else
            <div class="space-y-12">
                @foreach ($ministries as $ministry)
                    <div class="">
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $ministry->title }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($ministry->opportunities as $opportunity)
                                <div class="bg-white cursor-pointer border border-blue-100 rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    @if($opportunity->cover_image)
                                        <div class="h-48 bg-gray-200 overflow-hidden">
                                            <img src="{{ asset($opportunity->cover_image) }}"
                                                 alt="{{ $opportunity->program_name }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @endif

                                    <div class="p-6">
                                        <div class="mb-4">
                                            <h4 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">
                                                {{ $opportunity->program_name }}
                                            </h4>
                                            <div class="text-sm text-gray-600 line-clamp-3">
                                                {{ Str::limit($opportunity->participation_conditions, 120) }}
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            @if($opportunity->responsible_person && isset($opportunity->responsible_person['name']))
                                                <div class="text-xs text-gray-500">
                                                    <div class="font-medium">{{ $opportunity->responsible_person['name'] }}</div>
                                                    <div>{{ $opportunity->responsible_person['position'] ?? '' }}</div>
                                                </div>
                                            @endif

                                            <a href="{{ route('opportunities.show', $opportunity) }}"
                                               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                                Подробнее
                                                <x-lucide-arrow-right class="h-4 w-4" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($ministry->opportunities->count() > 6)
                            <div class="mt-6 text-center">
                                <a href="{{ route('opportunities.by-ministry', $ministry) }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                                    Все возможности от {{ $ministry->title }}
                                    <x-lucide-arrow-right class="h-4 w-4" />
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection
