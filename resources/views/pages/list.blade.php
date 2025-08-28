@extends('layouts.app')

@section('title', $title)

@section('content')

    <section class="bg-accentbg mt-[-80px] pt-[80px] min-h-[calc(100vh-200px)]">
        <div class="max-w-screen-xl mx-auto px-6">
            <div class="flex justify-between items-center mb-4 mt-12">
                <h2 class="text-3xl font-semibold text-gray-800">Все мероприятия</h2>
            </div>

            <x-filter entity="events" :count="$items->total()" />

            @if ($items != null && count($items) === 0)
                <x-empty class="w-full" title="По вашему запросу ничего не найдено." />
            @else
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($items as $event)
                        @switch($entity)
                            @case('events')
                                <x-event-card image="{{ asset($event->cover) }}" :tags="[$event->category, $event->type]"
                                    points="{{ $event->getPoints() }}" title="{{ $event->title }}"
                                    location="{{ $event->getAddress() }}" date="{{ $event->start }}"
                                    link="{{ route('event', $event->id) }}" />
                            @break

                            @default
                        @endswitch
                    @endforeach
                </div>
            @endif

            <div class="flex items-center justify-between mt-4 pb-4">
                <div class="text-sm text-gray-500">
                    Показано {{ $items->firstItem() }}–{{ $items->lastItem() }} из {{ $items->total() }}
                </div>

                {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
                {{ $items->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
            </div>
        </div>

    </section>

@endsection
