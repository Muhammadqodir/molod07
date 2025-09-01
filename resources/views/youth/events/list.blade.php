@extends('layouts.sidebar-layout')

@section('title', 'Мои мероприятия')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Мои мероприятия</h1>
    </div>

    @if ($participations->isEmpty())
        <x-empty title="Вы пока не участвуете в мероприятиях." />
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
                    @foreach ($participations as $item)
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

                            <td class="px-4 py-4 w-full">

                                <div class="flex items-center gap-2">
                                    {{-- <x-profile-pic :user="$item->partner" class="inline" size="w-8 h-8" /> --}}
                                    <div class="text-sm text-primary">
                                        {{-- сюда можно вывести партнёра/организацию при наличии --}}
                                        {{ $item->event->partner?->getFullName() ?? 'Организация' }}
                                        {{-- @if ($event->partnersProfile->verified ?? false) --}}
                                        <x-lucide-badge-check class="inline w-4 h-4 text-primary" />
                                        {{-- @endif --}}
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center">

                                    <a href="{{ route('event', $item->event->id) }}" target="_blank" title="Открыть"
                                        class="mr-2">
                                        <x-nav-icon>
                                            <x-lucide-external-link class="w-5 h-5 text-gray-500 hover:text-primary" />
                                        </x-nav-icon>
                                    </a>

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
            Показано {{ $participations->firstItem() }}–{{ $participations->lastItem() }} из {{ $participations->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $participations->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

@endsection
