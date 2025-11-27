@extends('layouts.sidebar-layout')

@section('title', 'Книжная полка')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Книжная полка</h1>
        <a href="{{ route('admin.books.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.books.index') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию, автору или описанию" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($books->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Книга</th>
                        <th class="text-left font-medium px-4 py-3">Автор</th>
                        <th class="text-left font-medium px-4 py-3">Статус</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($books as $item)
                        @php
                            /** @var \App\Models\Book $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="flex items-center gap-3">
                                    @if ($item->cover)
                                        <img src="{{ asset($item->cover) }}" alt="{{ $item->title }}"
                                            class="w-12 h-16 object-cover rounded-lg shadow-sm" />
                                    @else
                                        <div class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <x-lucide-book class="h-6 w-6 text-gray-400" />
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $item->title }}</div>
                                            @if ($item->link)
                                                <a href="{{ $item->link }}" target="_blank" title="Открыть книгу"
                                                    class="inline-flex items-center justify-center w-6 h-6 text-gray-400 hover:text-primary-600 transition">
                                                    <x-lucide-external-link class="h-4 w-4" />
                                                </a>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($item->description, 100) }}</div>
                                        @if ($item->creator)
                                            <div class="text-xs text-gray-400 mt-1">
                                                Добавил:
                                                @if ($item->creator->admin_profile)
                                                    {{ $item->creator->admin_profile->name }}
                                                @elseif ($item->creator->youth_profile)
                                                    {{ $item->creator->youth_profile->first_name }}
                                                    {{ $item->creator->youth_profile->last_name }}
                                                @elseif ($item->creator->partner_profile)
                                                    {{ $item->creator->partner_profile->organization_name }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <span class="text-gray-600">{{ $item->author }}</span>
                            </td>

                            <td class="px-4 py-4">
                                @if ($item->status === 'approved')
                                    <span
                                        class="px-2 py-1 text-xs bg-emerald-100 text-emerald-800 rounded-full">Одобрен</span>
                                @elseif ($item->status === 'pending')
                                    <span
                                        class="px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded-full">Ожидает</span>
                                @elseif ($item->status === 'rejected')
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Отклонен</span>
                                @elseif ($item->status === 'archived')
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Архив</span>
                                @endif
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-1">
                                    {{-- Edit button --}}
                                    <a href="{{ route('admin.books.edit', $item->id) }}" title="Редактировать"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-500 hover:text-primary-600 transition">
                                        <x-lucide-pencil class="h-4 w-4" />
                                    </a>

                                    {{-- Approve/Archive actions --}}
                                    @if ($item->status === 'pending')
                                        <form action="{{ route('admin.books.approve', $item->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit" title="Одобрить"
                                                class="inline-flex items-center justify-center w-8 h-8 text-gray-500 hover:text-emerald-600 transition">
                                                <x-lucide-check class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif

                                    @if ($item->status === 'approved')
                                        <form action="{{ route('admin.books.action.archive', $item->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit" title="В архив"
                                                class="inline-flex items-center justify-center w-8 h-8 text-gray-500 hover:text-amber-600 transition">
                                                <x-lucide-archive class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete button --}}
                                    <form action="{{ route('admin.books.destroy', $item->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Вы уверены, что хотите удалить эту книгу?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Удалить"
                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-500 hover:text-red-600 transition">
                                            <x-lucide-trash class="h-4 w-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    @endif
@endsection
