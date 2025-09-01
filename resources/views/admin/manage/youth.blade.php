@extends('layouts.sidebar-layout')

@section('title', 'Пользователи')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Пользователи</h1>
    </div>

    <form method="GET" action="{{ route('admin.manage.youth') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по имени или email" :value="old('q', request('q'))" />
        </div>
    </form>



    @if ($youth->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Данные о пользователе</th>
                        <th class="text-left font-medium px-4 py-3">Баллы</th>
                        <th class="text-left font-medium px-4 py-3">Контакты</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($youth as $item)
                        @php
                            /** @var \App\Models\User $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <x-profile-pic :user="$item" class="flex-none h-[50px] w-[50px] text-sm" />
                                    <div>
                                        <div class="text-gray-800 font-medium">{{ $item->getFullName() }}</div>
                                        <div class="text-gray-500 text-sm">ID: {{ $item->getUserId() }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Coins --}}
                            <td class="px-4 py-4 text-gray-700">
                                <x-lucide-coins class="inline-block w-4 h-4 mb-1" /> {{ $item->youthProfile->getMyPointsSum() }}
                            </td>

                            {{-- Contacts --}}
                            <td class="px-4 py-4">
                                <p> {{ $item->youthProfile->phone }} </p>
                                <p> {{ $item->email }} </p>
                            </td>

                            {{-- Actions --}}
                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center">

                                    <form method="POST"
                                        action="{{ $item->is_blocked ? route('admin.manage.youth.unblock') : route('admin.manage.youth.block') }}"
                                        onsubmit="return confirm('Вы уверены, что хотите {{ $item->is_blocked ? 'разблокировать' : 'заблокировать' }} этого пользователя?');">
                                        @csrf
                                        <input name="id" value="{{ $item->id }}" hidden>
                                        <button type="submit" class="p-0 m-0 bg-transparent border-0">
                                            @if ($item->is_blocked)
                                                <x-nav-icon>
                                                    <x-lucide-lock class="w-5 h-5" />
                                                </x-nav-icon>
                                            @else
                                                <x-nav-icon>
                                                    <x-lucide-unlock class="w-5 h-5" />
                                                </x-nav-icon>
                                            @endif
                                        </button>
                                    </form>

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
            Показано {{ $youth->firstItem() }}–{{ $youth->lastItem() }} из {{ $youth->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $youth->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

@endsection
