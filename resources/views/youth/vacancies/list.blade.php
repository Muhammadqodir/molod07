@extends('layouts.sidebar-layout')

@section('title', 'Мои отклики на вакансии')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Мои отклики на вакансии</h1>
    </div>

    @if ($responses->isEmpty())
        <x-empty title="Вы пока не откликались на вакансии." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Вакансия</th>
                        <th class="text-left font-medium px-4 py-3">Дата отклика</th>
                        <th class="text-left font-medium px-4 py-3">Предпочт. связь</th>
                        <th class="text-left font-medium px-4 py-3">Работодатель</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($responses as $response)
                        @php
                            /** @var \App\Models\VacancyResponse $response */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4 w-full">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <x-lucide-briefcase class="w-5 h-5 text-blue-600" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $response->vacancy->title }}</div>
                                        </div>
                                        <div class="text-gray-500 text-sm line-clamp-2 mt-1">
                                            {{ $response->vacancy->org_name }} • {{ $response->vacancy->getSalaryRange() }}
                                        </div>
                                        @if($response->vacancy->org_address)
                                            <div class="text-xs text-gray-400 mt-1">
                                                <x-lucide-map-pin class="w-3 h-3 inline" /> {{ $response->vacancy->org_address }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                <div class="text-sm">
                                    {{ $response->created_at->format('d.m.Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $response->created_at->format('H:i') }}
                                </div>
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                @php
                                    $contactIcons = [
                                        'phone' => 'phone',
                                        'email' => 'mail',
                                        'telegram' => 'send'
                                    ];
                                    $contactLabels = [
                                        'phone' => 'Телефон',
                                        'email' => 'Email',
                                        'telegram' => 'Telegram'
                                    ];
                                @endphp
                                <div class="flex items-center gap-2">
                                    <x-dynamic-component :component="'lucide-' . ($contactIcons[$response->prefered_contact] ?? 'phone')"
                                        class="w-4 h-4 text-gray-400" />
                                    <span class="text-sm">{{ $contactLabels[$response->prefered_contact] ?? 'Телефон' }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <x-profile-pic :user="$response->vacancy->user" class="w-8 h-8" />
                                    <div class="text-sm text-primary">
                                        {{ $response->vacancy->user->getFullName() ?? 'Работодатель' }}
                                        <x-lucide-badge-check class="inline w-4 h-4 text-primary" />
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('vacancy', $response->vacancy->id) }}" target="_blank" title="Открыть вакансию">
                                        <x-nav-icon>
                                            <x-lucide-external-link class="w-5 h-5 text-gray-500 hover:text-primary" />
                                        </x-nav-icon>
                                    </a>

                                    @if($response->resume_path)
                                        <a href="{{ asset($response->resume_path) }}" target="_blank" title="Скачать резюме">
                                            <x-nav-icon>
                                                <x-lucide-download class="w-5 h-5 text-gray-500 hover:text-primary" />
                                            </x-nav-icon>
                                        </a>
                                    @endif
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
            Показано {{ $responses->firstItem() }}–{{ $responses->lastItem() }} из {{ $responses->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $responses->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

@endsection
