@extends('layouts.sidebar-layout')

@section('title', 'Возможности')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Возможности</h1>
        <a href="{{ route('admin.opportunities.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <form method="GET" action="{{ route('admin.opportunities.index') }}" class="flex-1">
            <div class="relative">
                <x-search-input name="q" placeholder="Поиск по названию программы" :value="old('q', request('q'))" />
            </div>
            <input type="hidden" name="ministry_id" value="{{ request('ministry_id') }}">
        </form>

        <form method="GET" action="{{ route('admin.opportunities.index') }}" class="min-w-0 sm:w-64">
            <select name="ministry_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    onchange="this.form.submit()">
                <option value="">Все министерства</option>
                @foreach ($ministries as $ministry)
                    <option value="{{ $ministry->id }}" {{ request('ministry_id') == $ministry->id ? 'selected' : '' }}>
                        {{ $ministry->title }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="q" value="{{ request('q') }}">
        </form>
    </div>

    @if ($opportunities->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Программа</th>
                        <th class="text-left font-medium px-4 py-3">Министерство</th>
                        <th class="text-left font-medium px-4 py-3">Ответственное лицо</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($opportunities as $opportunity)
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4">
                                <div class="text-gray-800 font-medium line-clamp-2">{{ $opportunity->program_name }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $opportunity->ministry->title }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @if($opportunity->responsible_person)
                                    <div class="text-gray-800">{{ $opportunity->responsible_person['name'] ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $opportunity->responsible_person['position'] ?? '' }}</div>
                                @else
                                    <span class="text-gray-400">Не указано</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.opportunities.show', $opportunity) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <x-lucide-eye class="h-4 w-4" />
                                    </a>
                                    <a href="{{ route('admin.opportunities.edit', $opportunity) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <x-lucide-edit class="h-4 w-4" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.opportunities.destroy', $opportunity) }}"
                                          class="inline"
                                          onsubmit="return confirm('Вы уверены, что хотите удалить эту возможность?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
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

        <div class="mt-6">
            {{ $opportunities->links() }}
        </div>
    @endif
@endsection
