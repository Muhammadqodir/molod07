@extends('layouts.app')

@section('title', $title)

@section('content')

    <section class="bg-accentbg mt-[-80px] pt-[80px] min-h-[calc(100vh-200px)]">
        <div class="max-w-screen-xl mx-auto px-6">
            <div class="flex justify-between items-center mb-4 mt-12">
                <h2 class="text-3xl font-semibold text-gray-800">{{ $title }}</h2>
            </div>

            {{-- Search Bar --}}
            <div class="mb-6">
                <form method="GET" action="{{ route('books.list') }}" class="max-w-2xl">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Поиск по названию, автору или описанию..."
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            @if ($items != null && count($items) === 0)
                <x-empty class="w-full" title="По вашему запросу ничего не найдено." />
            @else
                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @foreach ($items as $book)
                        <div class="group cursor-pointer">
                            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden h-full flex flex-col">
                                {{-- Book Cover --}}
                                <div class="relative aspect-[2/3] overflow-hidden bg-gray-100">
                                    @if ($book->cover)
                                        <img src="{{ asset($book->cover) }}"
                                             alt="{{ $book->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5">
                                            <svg class="w-16 h-16 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Overlay with link on hover --}}
                                    @if ($book->link)
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <a href="{{ $book->link }}" target="_blank"
                                               class="px-4 py-2 bg-white text-primary rounded-lg font-medium hover:bg-primary hover:text-white transition-colors duration-200">
                                                Читать
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                {{-- Book Info --}}
                                <div class="p-3 flex-1 flex flex-col">
                                    <h3 class="font-semibold text-sm text-gray-800 line-clamp-2 mb-1 group-hover:text-primary transition-colors">
                                        {{ $book->title }}
                                    </h3>
                                    <p class="text-xs text-gray-500 line-clamp-1 mb-2">
                                        {{ $book->author }}
                                    </p>

                                    @if ($book->description)
                                        <p class="text-xs text-gray-600 line-clamp-3 mt-auto">
                                            {{ $book->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center justify-between mt-8 pb-8">
                <div class="text-sm text-gray-500">
                    Показано {{ $items->firstItem() }}–{{ $items->lastItem() }} из {{ $items->total() }}
                </div>

                {{-- Pagination --}}
                {{ $items->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
            </div>
        </div>

    </section>

@endsection
