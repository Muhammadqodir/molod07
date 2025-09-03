@extends('layouts.sidebar-layout')

@section('title', 'Отклики на вакансии')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Отклики на вакансии</h1>
        <a href="{{ route('partner.vacancies.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить вакансию</span>
        </a>
    </div>

    <form method="GET" action="{{ route('partner.vacancies.responses') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по вакансии или соискателю" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($responses->isEmpty())
        <x-empty title="На ваши вакансии пока нет откликов." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Соискатель</th>
                        <th class="text-left font-medium px-4 py-3">Вакансия</th>
                        <th class="text-left font-medium px-4 py-3">Дата отклика</th>
                        <th class="text-left font-medium px-4 py-3">Предпочт. связь</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($responses as $response)
                        @php
                            /** @var \App\Models\VacancyResponse $response */
                        @endphp
                        <tr class="border-t border-gray-100">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <x-profile-pic :user="$response->user" class="flex-none h-[50px] w-[50px] text-sm" />
                                    <div>
                                        <div class="text-gray-800 font-medium">{{ $response->user->getFullName() }}</div>
                                        <div class="text-gray-500 text-sm">ID: {{ $response->user->getUserId() }}</div>
                                        @if($response->user->youthProfile)
                                            <div class="text-xs text-gray-400">
                                                {{ $response->user->youthProfile->phone ?? $response->user->email }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 w-full">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <x-lucide-briefcase class="w-4 h-4 text-blue-600" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $response->vacancy->title }}</div>
                                        </div>
                                        <div class="text-gray-500 text-sm line-clamp-2 mt-1">
                                            {{ $response->vacancy->getSalaryRange() }}
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
                                    $contactValues = [
                                        'phone' => $response->user->youthProfile->phone ?? 'Не указан',
                                        'email' => $response->user->email,
                                        'telegram' => $response->user->youthProfile->telegram ?? 'Не указан'
                                    ];
                                @endphp
                                <div class="flex items-center gap-2 mb-1">
                                    <x-dynamic-component :component="'lucide-' . ($contactIcons[$response->prefered_contact] ?? 'phone')"
                                        class="w-4 h-4 text-gray-400" />
                                    <span class="text-sm font-medium">{{ $contactLabels[$response->prefered_contact] ?? 'Телефон' }}</span>
                                </div>
                                <div class="text-xs text-gray-600">
                                    {{ $contactValues[$response->prefered_contact] ?? 'Не указан' }}
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if($response->resume_path)
                                        <a href="{{ asset($response->resume_path) }}" target="_blank" title="Скачать резюме">
                                            <x-nav-icon>
                                                <x-lucide-download class="w-5 h-5 text-blue-600 hover:text-blue-800" />
                                            </x-nav-icon>
                                        </a>
                                    @endif

                                    <button onclick="showCoverLetter('{{ addslashes($response->cover_letter) }}')" title="Показать сопроводительное письмо">
                                        <x-nav-icon>
                                            <x-lucide-file-text class="w-5 h-5 text-gray-500 hover:text-primary" />
                                        </x-nav-icon>
                                    </button>

                                    <a href="{{ route('vacancy', $response->vacancy->id) }}" target="_blank" title="Открыть вакансию">
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
            Показано {{ $responses->firstItem() }}–{{ $responses->lastItem() }} из {{ $responses->total() }}
        </div>

        {{-- Пагинация (Tailwind-шаблон по умолчанию) --}}
        {{ $responses->onEachSide(1)->appends(request()->except('page'))->links('vendor.pagination') }}
    </div>

    {{-- Cover Letter Modal --}}
    <div id="coverLetterModal" class="fixed inset-0 z-50 hidden overflow-y-auto sm:w-full sm:max-w-2xl m-auto" style="z-index: 99999999999;">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="hideCoverLetter()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg">
                <div class="bg-white p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Сопроводительное письмо</h3>
                        <button onclick="hideCoverLetter()" class="text-gray-400 hover:text-gray-600">
                            <x-lucide-x class="w-6 h-6" />
                        </button>
                    </div>
                    <div id="coverLetterContent" class="text-sm text-gray-700 whitespace-pre-wrap max-h-64 overflow-y-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function showCoverLetter(content) {
        document.getElementById('coverLetterContent').textContent = content;
        document.getElementById('coverLetterModal').classList.remove('hidden');
    }

    function hideCoverLetter() {
        document.getElementById('coverLetterModal').classList.add('hidden');
    }
</script>
@endpush
