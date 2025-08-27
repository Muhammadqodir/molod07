@extends('layouts.app')

@section('title', $podcast->title)

@section('content')

    <section class="bg-accentbg mt-[-80px] pt-[80px]">
        <div class="max-w-6xl mx-auto py-6 space-y-8" x-data="{ tab: 'info', roleIdx: 0 }">

            {{-- Top: cover + header --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-4 md:p-6">
                    {{-- Cover --}}
                    <div class="md:col-span-4">
                        <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden">
                            @if ($podcast->cover)
                                <img src="{{ asset($podcast->cover) }}" alt="cover" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <x-lucide-headphones class="w-16 h-16" />
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Main info --}}
                    <div class="md:col-span-8 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <div class="flex items-center gap-3 flex-wrap">
                                @if ($podcast->category)
                                    <span
                                        class="inline-flex items-center gap-1 bg-[#E2ECFF] text-gray-700 px-3 py-1 rounded-xl">
                                        <?php $categories = config('events.categories'); ?>
                                        @foreach ($categories as $item)
                                            @if ($item['label'] == $podcast->category)
                                                <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-4 h-4" />
                                            @endif
                                        @endforeach
                                        <span class="mt-0.5"> {{ $podcast->category }}</span>
                                    </span>
                                @endif
                                @if ($podcast->created_at)
                                    <span class="inline-flex items-center gap-1 text-gray-500 align-middle">
                                        <x-lucide-calendar class="w-4 h-4" style="vertical-align: middle;" />
                                        <span class="align-middle mt-0.5">{{ $podcast->created_at->format('d.m.Y') }}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 flex-shrink-0">
                                <button type="button" class="p-2 hover:bg-gray-100 rounded-xl" title="Поделиться">
                                    <x-lucide-share-2 class="w-5 h-5" />
                                </button>
                                <button type="button" class="p-2 hover:bg-gray-100 rounded-xl" title="В избранное">
                                    <x-lucide-heart class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-semibold leading-tight">
                            {{ $podcast->title }}
                        </h1>

                        <p class="text-gray-600 text-sm flex gap-4 md:text-base">

                            @if ($podcast->length)
                                <span class="inline-flex items-center gap-1 text-gray-500 align-middle">
                                    <x-lucide-clock class="w-4 h-4" style="vertical-align: middle;" />
                                    <span class="align-middle mt-0.5">{{ $podcast->length }} мин. </span>
                                </span>
                            @endif
                            @if ($podcast->episode_numbers)
                                <span class="inline-flex items-center gap-1 text-gray-500 align-middle">
                                    <x-lucide-layers class="w-4 h-4" style="vertical-align: middle;" />
                                    <span class="align-middle mt-0.5">{{ $podcast->episode_numbers }} выпусков </span>
                                </span>
                            @endif

                            @if ($podcast->user)
                                <span class="inline-flex items-center gap-1 text-gray-500 align-middle">
                                    <x-lucide-user class="w-4 h-4" style="vertical-align: middle;" />
                                    <span class="align-middle mt-0.5">{{ $podcast->user->getFullName() ?? 'Неизвестен' }}</span>
                                </span>
                            @endif
                        </p>

                        @if ($podcast->short_description)
                            <p class="text-gray-700 text-sm md:text-base">
                                {{ $podcast->short_description }}
                            </p>
                        @endif

                        <div class="mt-auto flex items-center justify-between gap-3">
                            {{-- Podcast Player Controls --}}
                            <div class="flex items-center gap-3">
                                @if ($podcast->link)
                                    <a href="{{ $podcast->link }}" target="_blank"
                                        class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl hover:bg-primary/90 transition-colors">
                                        <x-lucide-play class="w-4 h-4" />
                                        <span class="mt-0.5">Слушать</span>
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
