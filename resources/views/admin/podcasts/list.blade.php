@extends('layouts.sidebar-layout')

@section('title', 'Подкасты')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Подкасты</h1>
        <a href="{{ route('admin.podcasts.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.podcasts.index') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию, описанию или категории" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($podcasts->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Подкаст</th>
                        <th class="text-left font-medium px-4 py-3">Категория</th>
                        <th class="text-left font-medium px-4 py-3">Длительность</th>
                        <th class="text-left font-medium px-4 py-3">Статус</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($podcasts as $item)
                        @php
                            /** @var \App\Models\Podcast $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($item->cover) }}" alt="{{ $item->title }}"
                                        class="w-10 h-10 object-cover rounded-lg" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $item->title }}</div>
                                            <a href="{{ route('admin.podcasts.preview', $item->id) }}" title="Открыть"
                                                target="_blank">
                                                <x-lucide-eye class="h-4 w-4 text-gray-400 hover:text-gray-600" />
                                            </a>
                                            <a href="{{ $item->link }}" title="Прослушать подкаст" target="_blank">
                                                <x-lucide-external-link class="h-4 w-4 text-gray-400 hover:text-gray-600" />
                                            </a>
                                        </div>
                                        <div class="text-gray-500 text-sm line-clamp-2 mt-1">{{ $item->short_description }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $item->category }}
                                </span>
                            </td>

                            <td class="px-4 py-4">
                                <span class="text-gray-600">{{ $item->length }}</span>
                            </td>

                            <td class="px-4 py-4">
                                @php
                                    $statusConfig = [
                                        'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'На рассмотрении'],
                                        'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Одобрено'],
                                        'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Отклонено'],
                                        'archived' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Архивировано'],
                                    ];
                                    $config = $statusConfig[$item->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => $item->status];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['class'] }}">
                                    {{ $config['text'] }}
                                </span>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">

                                    @switch($item->status)
                                        @case('approved')
                                            <form method="POST" action="{{ route('admin.news.action.archive', $item->id) }}"
                                                class="mr-2">
                                                @csrf
                                                <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Архивировать">
                                                    <x-nav-icon>
                                                        <x-lucide-archive class="w-5 h-5 text-blue-600" />
                                                    </x-nav-icon>
                                                </button>
                                            </form>
                                        @break

                                        @case('pending')
                                            <form method="POST" action="{{ route('admin.news.approve', $item->id) }}"
                                                class="mr-2">
                                                @csrf
                                                <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Одобрить">
                                                    <x-nav-icon>
                                                        <x-lucide-check class="w-5 h-5 text-green-600" />
                                                    </x-nav-icon>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.news.reject', $item->id) }}"
                                                class="mr-2">
                                                @csrf
                                                <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Отклонить">
                                                    <x-nav-icon>
                                                        <x-lucide-x class="w-5 h-5 text-red-600" />
                                                    </x-nav-icon>
                                                </button>
                                            </form>
                                        @break

                                        @default
                                    @endswitch

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $podcasts->links() }}
        </div>
    @endif

    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
             x-data="{ show: true }"
             x-show="show"
             x-transition
             x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
    @endif
@endsection
