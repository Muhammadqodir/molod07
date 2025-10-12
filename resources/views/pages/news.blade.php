@extends('layouts.app')

@section('title', $news->title)

@section('content')

    <section class="bg-accentbg mt-[-80px] pt-[80px]" data-track-view data-viewable-type="App\Models\News" data-viewable-id="{{ $news->id }}">
        <div class="max-w-6xl mx-auto py-6 space-y-8" x-data="{ tab: 'info', roleIdx: 0 }">

            {{-- Top: cover + header --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-4 md:p-6">
                    {{-- Cover --}}
                    <div class="md:col-span-4">
                        <div class="aspect-[4/3] bg-gray-100 rounded-2xl overflow-hidden">
                            @if ($news->cover)
                                <img src="{{ asset($news->cover) }}" alt="cover" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>

                    {{-- Main info --}}
                    <div class="md:col-span-8 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <div class="flex items-center gap-3 flex-wrap">
                                @if ($news->category)
                                    <span
                                        class="inline-flex items-center gap-1 bg-[#E2ECFF] text-gray-700 px-3 py-1 rounded-xl">
                                        <?php $categories = config('events.categories'); ?>
                                        @foreach ($categories as $item)
                                            @if ($item['label'] == $news->category)
                                                <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-4 h-4" />
                                            @endif
                                        @endforeach
                                        <span class="mt-0.5"> {{ $news->category }}</span>
                                    </span>
                                @endif
                                @if ($news->created_at)
                                    <span class="inline-flex items-center gap-1 text-gray-500 align-middle">
                                        <x-lucide-calendar class="w-4 h-4" style="vertical-align: middle;" />
                                        <span class="align-middle mt-0.5">{{ $news->created_at->format('d.m.Y') }}</span>
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 flex-shrink-0">

                                <button type="button" class="share-button p-2 hover:bg-gray-100 rounded-xl"
                                        title="Поделиться"
                                        data-share-title="{{ $news->title }}"
                                        data-share-url="{{ request()->fullUrl() }}">
                                    <x-lucide-share-2 class="w-5 h-5" />
                                </button>
                                <button type="button" class="p-2 hover:bg-gray-100 rounded-xl" title="В избранное">
                                    <x-lucide-heart class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-semibold leading-tight">
                            {{ $news->title }}
                        </h1>

                        <p class="text-gray-600 text-sm md:text-base">
                            Автор: <span class="text-primary"> {{ $news->user->getFullName() ?? 'Неизвестен' }}</span>
                        </p>

                        <div class="mt-auto flex items-center justify-between gap-3">

                            <div class="flex items-center gap-2 text-gray-500">
                                <span class="inline-flex items-center gap-1 text-gray-500">
                                    <x-lucide-eye class="w-4 h-4" />
                                    <span class="mt-0.5 views-count">{{ $news->views_count }}</span>
                                </span>
                                <span class="inline-flex items-center gap-1 text-gray-500">
                                    <x-lucide-message-circle class="w-4 h-4" />
                                    <span class="mt-0.5 comments-count">{{ $news->comments_count }}</span>
                                </span>
                                <span class="inline-flex items-center gap-1 text-gray-500">
                                    <x-lucide-heart class="w-4 h-4" />
                                    <span class="mt-0.5">{{ $news->likes_count }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section tabs --}}
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">
                {!! $news->description !!}


                Автор
                <div class="flex items-center gap-2">
                    <x-profile-pic :user="$news->user" size="w-12 h-12" />
                    <div class="text-sm text-primary">
                        {{-- сюда можно вывести партнёра/организацию при наличии --}}
                        {{ $news->user->getFullName() ?? 'Организация' }}
                        {{-- @if ($event->partnersProfile->verified ?? false) --}}
                        <x-lucide-badge-check class="inline w-4 h-4 text-primary" />
                        {{-- @endif --}}
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl md:text-2xl font-semibold leading-tight mt-6">Понравилась публикация? </h2>
                <div class="flex items-center gap-2 mt-2">
                    <button type="button"
                            class="likes-button flex items-center gap-2 text-gray-600 bg-white rounded-xl py-2 px-4 hover:bg-gray-50 transition-colors {{ $news->hasUserLiked() ? 'active bg-blue-50 text-blue-600' : '' }}"
                            data-likeable-type="App\Models\News"
                            data-likeable-id="{{ $news->id }}">
                        <x-lucide-thumbs-up class="w-5 h-5" />
                        <span class="mt-0.5 likes-count">{{ $news->likes_count }}</span>
                    </button>
                    <button type="button"
                            class="dislikes-button flex items-center gap-2 text-gray-600 bg-white rounded-xl py-2 px-4 hover:bg-gray-50 transition-colors {{ $news->hasUserDisliked() ? 'active bg-red-50 text-red-600' : '' }}"
                            data-likeable-type="App\Models\News"
                            data-likeable-id="{{ $news->id }}">
                        <x-lucide-thumbs-down class="w-5 h-5" />
                        <span class="mt-0.5 dislikes-count">{{ $news->dislikes_count }}</span>
                    </button>
                </div>
            </div>

            <div>
                <h2 class="text-xl md:text-2xl font-semibold leading-tight mt-6">Комментарии </h2>
                <div class="mt-2 mb-2">
                    <x-comment-form
                        :commentable-type="'App\\Models\\News'"
                        :commentable-id="$news->id"
                        placeholder="Введите текст комментария" />
                </div>

                <div class="comments-container mt-6" data-load-comments data-commentable-type="App\Models\News" data-commentable-id="{{ $news->id }}">
                    {{-- Комментарии будут загружены здесь через JavaScript --}}
                </div>
            </div>
        </div>

    </section>
@endsection
