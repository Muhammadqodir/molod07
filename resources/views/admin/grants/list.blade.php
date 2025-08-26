@extends('layouts.sidebar-layout')

@section('title', 'Гранты')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Гранты</h1>
        <a href="{{ route('admin.grants.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.grants.index') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($grants->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Название</th>
                        <th class="text-left font-medium px-4 py-3">Статус</th>
                        <th class="text-left font-medium px-4 py-3">Категория</th>
                        <th class="text-left font-medium px-4 py-3">Дедлайн</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($grants as $item)
                        @php
                            /** @var \App\Models\Grant $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($item->cover) }}" alt="{{ $item->title }}"
                                        class="w-10 h-10 object-cover rounded-lg" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $item->title }}</div>
                                            <a href="{{ route('admin.grants.preview', $item->id) }}" title="Открыть"
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

                            <td class="px-4 py-4 text-gray-700">
                                <span class="text-sm">{{ $item->category }}</span>
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                <span class="text-sm">{{ $item->deadline ? $item->deadline->format('d.m.Y') : '—' }}</span>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center">

                                    @switch($item->status)
                                        @case('approved')
                                            <form method="POST" action="{{ route('admin.grants.action.archive', $item->id) }}"
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
                                            <form method="POST" action="{{ route('admin.grants.approve', $item->id) }}"
                                                class="mr-2">
                                                @csrf
                                                <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Одобрить">
                                                    <x-nav-icon>
                                                        <x-lucide-check class="w-5 h-5 text-green-600" />
                                                    </x-nav-icon>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.grants.reject', $item->id) }}"
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
    @endif


    <div class="flex items-center justify-between mt-4">
        <div class="text-sm text-gray-500">
            Показано {{ $grants->firstItem() }}–{{ $grants->lastItem() }} из {{ $grants->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $grants->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

@endsection
