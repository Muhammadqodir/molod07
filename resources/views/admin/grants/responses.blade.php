@extends('layouts.sidebar-layout')

@section('title', 'Отклики на гранты')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-3xl">Отклики на гранты</h1>
        <a href="{{ route('partner.grants.create') }}"
            class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10">
            <x-lucide-plus class="h-5" />
            <span class="hidden sm:inline">Добавить грант</span>
        </a>
    </div>

    <form method="GET" action="{{ route('partner.grants.responses') }}" class="mb-6">
        <div class="relative mt-4">
            <x-search-input name="q" placeholder="Поиск по гранту или соискателю" :value="old('q', request('q'))" />
        </div>
    </form>

    @if ($responses->isEmpty())
        <x-empty title="На ваши гранты пока нет откликов." />
    @else
        {{-- Table --}}
        <div class="overflow-x-auto overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">Кандидат</th>
                        <th class="text-left font-medium px-4 py-3">Грант</th>
                        <th class="text-left font-medium px-4 py-3">Дата отклика</th>
                        <th class="text-left font-medium px-4 py-3">Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($responses as $response)
                        @php
                            /** @var \App\Models\GrantApplication $response */
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
                                            <div class="text-gray-800 font-medium line-clamp-1">{{ $response->grant->title }}</div>
                                        </div>
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

                            <td class="px-2 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    <button onclick="showCoverLetter('{{ addslashes($response->comment) }}')" title="Показать сопроводительное письмо">
                                        <x-nav-icon>
                                            <x-lucide-file-text class="w-5 h-5 text-gray-500 hover:text-primary" />
                                        </x-nav-icon>
                                    </button>

                                    <a href="{{ route('vacancy', $response->grant->id) }}" target="_blank" title="Открыть грант">
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
