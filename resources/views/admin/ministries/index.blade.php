@extends('layouts.sidebar-layout')

@section('title', 'Министерства')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Министерства</h1>
        <a href="{{ route('admin.ministries.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.ministries.index') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($ministries->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Название</th>
                        <th class="text-left font-medium px-4 py-3">Описание</th>
                        <th class="text-left font-medium px-4 py-3">Возможности</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($ministries as $ministry)
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4">
                                <div class="text-gray-800 font-medium">{{ $ministry->title }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-gray-600 line-clamp-2">
                                    {{ Str::limit($ministry->description, 100) }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $ministry->opportunities_count }} возможностей
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.ministries.edit', $ministry) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <x-lucide-edit class="h-4 w-4" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.ministries.destroy', $ministry) }}"
                                          class="inline"
                                          onsubmit="return confirm('Вы уверены, что хотите удалить это министерство?')">
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
            {{ $ministries->links() }}
        </div>
    @endif
@endsection
