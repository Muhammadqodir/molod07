@extends('layouts.sidebar-layout')

@section('title', 'Новости')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Новости</h1>
        <a href="{{ route('admin.news.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route('main') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($news->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Название</th>
                        <th class="text-left font-medium px-4 py-3">Статус</th>
                        <th class="text-left font-medium px-4 py-3">Организатор</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($news as $item)
                        @php
                            /** @var \App\Models\News $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $item->cover) }}" alt="{{ $item->title }}"
                                        class="w-10 h-10 object-cover rounded-lg" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium">{{ $item->title }}</div>
                                            <a href="{{ route('admin.events.preview', $item->id) }}" title="Открыть"
                                                target="_blank">
                                                <x-lucide-external-link class="w-4 h-4 text-gray-500 hover:text-primary" />
                                            </a>
                                        </div>
                                        <div class="text-gray-500 text-sm line-clamp-2">{{ $item->short_description }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                @php
                                    $statusColors = [
                                        'approved' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'archived' => 'bg-gray-200 text-gray-700',
                                    ];
                                    $colorClass = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $colorClass }}">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td class="px-4 py-4 w-full">

                                <div class="flex items-center gap-2">
                                    {{-- <x-profile-pic :user="$item->partner" class="inline" size="w-8 h-8" /> --}}
                                    <div class="text-sm text-primary">
                                        {{-- сюда можно вывести партнёра/организацию при наличии --}}
                                        {{ $item->author->getFullName() ?? 'Организация' }}
                                        {{-- @if ($event->partnersProfile->verified ?? false) --}}
                                        <x-lucide-badge-check class="inline w-4 h-4 text-primary" />
                                        {{-- @endif --}}
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center">

                                    <form method="POST" action="{{ route('admin.events.approve', $item->id) }}"
                                        class="mr-2">
                                        @csrf
                                        <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Одобрить">
                                            <x-nav-icon>
                                                <x-lucide-check class="w-5 h-5 text-green-600" />
                                            </x-nav-icon>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.events.reject', $item->id) }}"
                                        class="mr-2">
                                        @csrf
                                        <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Отклонить">
                                            <x-nav-icon>
                                                <x-lucide-x class="w-5 h-5 text-red-600" />
                                            </x-nav-icon>
                                        </button>
                                    </form>

                                    {{-- <x-nav-icon>
                                        <x-lucide-pen class="w-5 h-5" />
                                    </x-nav-icon>

                                    <form method="POST" action="{{ route('admin.manage.youth.remove') }}"
                                        onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');">
                                        @csrf
                                        <input name="id" value="{{ $item->id }}" hidden>
                                        <button type="submit" class="p-0 m-0 bg-transparent border-0">
                                            <x-nav-icon>
                                                <x-lucide-trash-2 class="w-5 h-5" />
                                            </x-nav-icon>
                                        </button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endif


    <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-500">
            Показано {{ $events->firstItem() }}–{{ $events->lastItem() }} из {{ $events->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $events->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

@endsection
