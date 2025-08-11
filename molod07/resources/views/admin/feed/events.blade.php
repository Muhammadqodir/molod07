@extends('layouts.sidebar-layout')

@section('title', 'Мероприятия')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Мероприятия</h1>
        <a href="{{ route('admin.feed.events.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.feed.events') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию" :value="old('q', request('q'))" />
        </div>
    </form>



    @if ($admins->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Название</th>
                        <th class="text-left font-medium px-4 py-3">ID</th>
                        <th class="text-left font-medium w-full px-4 py-3">Привелегии</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($admins as $item)
                        @php
                            /** @var \App\Models\User $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <x-profile-pic :user="$item" class="flex-none h-[50px] w-[50px] text-sm" />
                                    <div>
                                        <div class="text-gray-800 font-medium">{{ $item->getFullName() }}</div>
                                        <div class="text-gray-500 text-sm">{{ $item->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- ID --}}
                            <td class="px-4 py-4 text-gray-700">
                                {{ $item->getUserId() }}
                            </td>

                            {{-- Actions (tags) --}}
                            <td class="px-4 py-4">
                                <x-badges-clamped :items="$item->adminsProfile->getPermissions()" :limit="3" :title="$item->getFullName()" />
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center">
                                    <x-nav-icon>
                                        <x-lucide-ban class="w-5 h-5" />
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
                                    </form>
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
            Показано {{ $admins->firstItem() }}–{{ $admins->lastItem() }} из {{ $admins->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $admins->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

@endsection
