@extends('layouts.sidebar-layout')

@section('title', 'Участники мероприятий')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Участники мероприятий</h1>
        <a href="{{ route(Auth::user()->role . '.events.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route(Auth::user()->role . '.events.index') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию мероприятия" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($participants->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Мероприятие</th>
                        <th class="text-left font-medium px-4 py-3">Пользователь</th>
                        <th class="text-left font-medium px-4 py-3">Статус</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($participants as $item)
                        @php
                            /** @var \App\Models\Participant $item */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($item->event->cover) }}" alt="{{ $item->event->title }}"
                                        class="w-10 h-10 object-cover rounded-lg" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $item->event->title }}
                                            </div>
                                        </div>
                                        <div class="text-gray-500 text-sm line-clamp-2">
                                            {{ $item->event->short_description }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $item->getRoleTitle() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <x-profile-pic :user="$item->user" class="flex-none h-[50px] w-[50px] text-sm" />
                                    <div>
                                        <div class="text-gray-800 font-medium">{{ $item->user->getFullName() }}</div>
                                        <div class="text-gray-500 text-sm">ID: {{ $item->user->getUserId() }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                @php
                                    $statusColors = [
                                        'approved' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $colorClass = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $colorClass }}">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center">

                                    @switch($item->status)
                                        @case('approved')
                                            <form method="POST"
                                                action="{{ route('partner.events.participants.accure', $item->id) }}"
                                                class="mr-2">
                                                @csrf
                                                <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Начислить баллы">
                                                    <x-nav-icon>
                                                        <x-lucide-hand-coins class="w-5 h-5 text-green-600" />
                                                    </x-nav-icon>
                                                </button>
                                            </form>
                                        @break

                                        @case('pending')
                                            <form method="POST"
                                                action="{{ route('partner.events.participants.approve', $item->id) }}"
                                                class="mr-2">
                                                @csrf
                                                <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Одобрить">
                                                    <x-nav-icon>
                                                        <x-lucide-check class="w-5 h-5 text-green-600" />
                                                    </x-nav-icon>
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('partner.events.participants.reject', $item->id) }}"
                                                class="mr-2">
                                                @csrf
                                                <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Отклонить">
                                                    <x-nav-icon>
                                                        <x-lucide-x class="w-5 h-5 text-red-600" />
                                                    </x-nav-icon>
                                                </button>
                                            </form>
                                        @break

                                        @case('rejected')
                                            <form method="POST"
                                                action="{{ route('partner.events.participants.approve', $item->id) }}"
                                                class="mr-2">
                                                @csrf
                                                <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Одобрить">
                                                    <x-nav-icon>
                                                        <x-lucide-check class="w-5 h-5 text-green-600" />
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
            Показано {{ $participants->firstItem() }}–{{ $participants->lastItem() }} из {{ $participants->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $participants->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

@endsection
