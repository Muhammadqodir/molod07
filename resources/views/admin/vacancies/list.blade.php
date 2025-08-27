@extends('layouts.sidebar-layout')

@section('title', 'Вакансии')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Вакансии</h1>
        <a href="{{ route(Auth::user()->role . '.vacancies.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить</span>
        </a>
    </div>

    <form method="GET" action="{{ route(Auth::user()->role . '.vacancies.index') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по названию или организации" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($vacancies->isEmpty())
        <x-empty title="По вашему запросу ничего не найдено." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Название</th>
                        <th class="text-left font-medium px-4 py-3">Организация</th>
                        <th class="text-left font-medium px-4 py-3">Зарплата</th>
                        <th class="text-left font-medium px-4 py-3">Статус</th>
                        <th class="text-left font-medium px-4 py-3">Автор</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($vacancies as $vacancy)
                        @php
                            /** @var \App\Models\Vacancy $vacancy */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <x-lucide-briefcase class="w-6 h-6 text-blue-600" />
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $vacancy->title }}</div>
                                            <a href="{{ route('admin.vacancies.preview', $vacancy->id) }}" title="Открыть"
                                                target="_blank">
                                                <x-lucide-external-link class="w-4 h-4 text-gray-500 hover:text-primary" />
                                            </a>
                                        </div>
                                        <div class="text-gray-500 text-sm">
                                            {{ $vacancy->type }} • {{ $vacancy->experience }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <div class="text-gray-800 font-medium">{{ $vacancy->org_name }}</div>
                                <div class="text-gray-500 text-sm">{{ $vacancy->category }}</div>
                            </td>

                            <td class="px-4 py-4">
                                @if ($vacancy->salary_negotiable)
                                    <span class="text-gray-600">По договорённости</span>
                                @else
                                    <div class="text-gray-800">
                                        @if ($vacancy->salary_from && $vacancy->salary_to)
                                            {{ number_format($vacancy->salary_from) }} -
                                            {{ number_format($vacancy->salary_to) }} ₽
                                        @elseif($vacancy->salary_from)
                                            от {{ number_format($vacancy->salary_from) }} сом
                                        @elseif($vacancy->salary_to)
                                            до {{ number_format($vacancy->salary_to) }} сом
                                        @else
                                            Не указана
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-4">
                                @php
                                    $statusColors = [
                                        'approved' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'archived' => 'bg-gray-200 text-gray-700',
                                    ];
                                    $colorClass = $statusColors[$vacancy->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $colorClass }}">
                                    {{ $vacancy->status }}
                                </span>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="text-sm text-primary">
                                        {{ $vacancy->user->getFullName() ?? 'Организация' }}
                                        <x-lucide-badge-check class="inline w-4 h-4 text-primary" />
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center">

                                    @switch($vacancy->status)
                                        @case('approved')
                                            <form method="POST"
                                                action="{{ route(Auth::user()->role . '.vacancies.action.archive', $vacancy->id) }}"
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
                                            @if (Auth::user()->role === 'admin')
                                                <form method="POST" action="{{ route('admin.vacancies.approve', $vacancy->id) }}"
                                                    class="mr-2">
                                                    @csrf
                                                    <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Одобрить">
                                                        <x-nav-icon>
                                                            <x-lucide-check class="w-5 h-5 text-green-600" />
                                                        </x-nav-icon>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.vacancies.reject', $vacancy->id) }}"
                                                    class="mr-2">
                                                    @csrf
                                                    <button type="submit" class="p-0 m-0 bg-transparent border-0"
                                                        title="Отклонить">
                                                        <x-nav-icon>
                                                            <x-lucide-x class="w-5 h-5 text-red-600" />
                                                        </x-nav-icon>
                                                    </button>
                                                </form>
                                            @endif
                                        @break

                                        @case('rejected')
                                            @if (Auth::user()->role === 'partner')
                                                <form method="POST"
                                                    action="{{ route('partner.vacancies.action.remove', $vacancy->id) }}" class="mr-2">
                                                    @csrf
                                                    <button type="submit" class="p-0 m-0 bg-transparent border-0" title="Удалить"
                                                        onclick="return confirm('Вы уверены, что хотите удалить это мероприятие?');">
                                                        <x-nav-icon>
                                                            <x-lucide-trash-2 class="w-5 h-5 text-red-600" />
                                                        </x-nav-icon>
                                                    </button>
                                                </form>
                                            @endif
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
            Показано {{ $vacancies->firstItem() }}–{{ $vacancies->lastItem() }} из {{ $vacancies->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $vacancies->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

@endsection
