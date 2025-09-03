@extends('layouts.sidebar-layout')

@section('title', 'Курсы')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Курсы</h1>
        <a href="{{ route('admin.education.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.education.index') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию, описанию или категории" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($courses->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Курс</th>
                        <th class="text-left font-medium px-4 py-3">Категория</th>
                        <th class="text-left font-medium px-4 py-3">Длительность</th>
                        <th class="text-left font-medium px-4 py-3">Модули</th>
                        <th class="text-left font-medium px-4 py-3">Статус</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($courses as $item)
                        @php
                            /** @var \App\Models\Course $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($item->cover) }}" alt="{{ $item->title }}"
                                        class="w-10 h-10 object-cover rounded-lg" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $item->title }}</div>
                                            <a href="{{ route('admin.education.preview', $item->id) }}" title="Открыть"
                                                class="inline-flex items-center justify-center w-6 h-6 text-gray-400 hover:text-primary-600 transition">
                                                <x-lucide-external-link class="h-4 w-4" />
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500 line-clamp-1">{{ $item->short_description }}</div>
                                        @if ($item->creator)
                                            <div class="text-xs text-gray-400">
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
                                <span class="text-gray-600">{{ $item->category }}</span>
                            </td>

                            <td class="px-4 py-4">
                                <span class="text-gray-600">{{ $item->length }}</span>
                            </td>

                            <td class="px-4 py-4">
                                <span class="text-gray-600">{{ $item->module_count }}</span>
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
                                    @if ($item->status === 'pending')
                                        <form action="{{ route('admin.education.approve', $item->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit" title="Одобрить"
                                                class="inline-flex items-center justify-center w-8 h-8 text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                                <x-lucide-check class="h-4 w-4" />
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.education.reject', $item->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit" title="Отклонить"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <x-lucide-x class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif

                                    @if ($item->status === 'approved')
                                        <form action="{{ route('admin.education.action.archive', $item->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Архивировать"
                                                class="inline-flex items-center justify-center w-8 h-8 text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                                <x-lucide-archive class="h-4 w-4" />
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.education.destroy', $item->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Вы уверены, что хотите удалить этот курс?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Удалить"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <x-lucide-trash-2 class="h-4 w-4" />
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
        @if ($courses->hasPages())
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        @endif
    @endif

@endsection
