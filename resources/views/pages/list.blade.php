@extends('layouts.app')

@section('title', $title)

@section('content')

    <section class="bg-accentbg mt-[-80px] pt-[80px] min-h-[calc(100vh-200px)]">
        <div class="max-w-screen-xl mx-auto px-6">
            <div class="flex justify-between items-center mb-4 mt-12">
                <h2 class="text-3xl font-semibold text-gray-800">{{ $title }}</h2>
            </div>

            <x-filter entity="{{ $entity }}" :count="$items->total()" />

            @if ($items != null && count($items) === 0)
                <x-empty class="w-full" title="По вашему запросу ничего не найдено." />
            @else
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($items as $item)
                        @switch($entity)
                            @case('events')
                                <x-event-card image="{{ asset($item->cover) }}" :tags="[$item->category, $item->type]" points="{{ $item->getPoints() }}"
                                    title="{{ $item->title }}" location="{{ $item->getAddress() }}" date="{{ $item->start ? \Carbon\Carbon::parse($item->start)->format('d.m.Y') : $item->created_at->format('d.m.Y') }}"
                                    link="{{ route('event', $item->id) }}" />
                            @break

                            @case('vacancies')
                                <x-vacancy-card title="{{ $item->title }}" category="{{ $item->category }}"
                                    date="{{ $item->created_at->format('d.m.Y') }}" salary="{{ $item->getSalaryRange() }}"
                                    location="{{ $item->org_address }}" link="{{ route('vacancy', $item->id) }}" />
                            @break

                            @case('courses')
                                <x-course-card image="{{ asset($item->cover) }}" category="{{ $item->category }}"
                                    title="{{ $item->title }}" description="{{ $item->short_description }}"
                                    length="{{ $item->length }}" modules="{{ $item->module_count }}"
                                    link="{{ route('course', $item->id) }}" />
                            @break

                            @case('news')
                                <x-news-card
                                    image="{{ asset($item->cover) }}"
                                    category="{{ $item->category }}"
                                    title="{{ $item->title }}"
                                    description="{{ $item->short_description }}"
                                    date="{{ $item->publication_date ? \Carbon\Carbon::parse($item->publication_date)->format('d.m.Y') : $item->created_at->format('d.m.Y') }}"
                                    link="{{ route('news', $item->id) }}" />
                            @break

                            @case('podcasts')
                                <x-podcast-card
                                    image="{{ asset($item->cover) }}"
                                    category="{{ $item->category }}"
                                    title="{{ $item->title }}"
                                    description="{{ $item->short_description }}"
                                    length="{{ $item->length }}"
                                    episodeNumbers="{{ $item->episode_numbers }}"
                                    link="{{ route('podcast', $item->id) }}" />
                            @break

                            @case('grants')
                                <x-grant-card
                                    image="{{ asset($item->cover) }}"
                                    category="{{ $item->category }}"
                                    title="{{ $item->title }}"
                                    description="{{ $item->short_description }}"
                                    link="{{ route('grant', $item->id) }}" />
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
