@extends('layouts.sidebar-layout')

@section('title', 'Просмотр возможности')

@section('content')
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.opportunities.index') }}"
           class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">
            <x-lucide-arrow-left class="h-5 w-5" />
        </a>
        <h1 class="text-3xl">Просмотр возможности</h1>
        <div class="ml-auto flex items-center gap-2">
            <a href="{{ route('admin.opportunities.edit', $opportunity) }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition">
                <x-lucide-edit class="h-4 w-4" />
                Редактировать
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-900">{{ $opportunity->program_name }}</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ $opportunity->ministry->title }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Условия участия</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($opportunity->participation_conditions)) !!}
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Сроки реализации</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($opportunity->implementation_period)) !!}
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Необходимые документы</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($opportunity->required_documents)) !!}
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    @if($opportunity->legal_documents && count($opportunity->legal_documents) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Нормативно-правовые документы</h3>
                            <div class="space-y-2">
                                @foreach($opportunity->legal_documents as $document)
                                    @if(!empty($document['title']) && !empty($document['link']))
                                        <a href="{{ $document['link'] }}"
                                           target="_blank"
                                           class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                            <x-lucide-external-link class="h-4 w-4 text-blue-600" />
                                            <span class="text-sm text-gray-900">{{ $document['title'] }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($opportunity->responsible_person)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Ответственное лицо</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-user class="h-4 w-4 text-gray-600" />
                                        <span class="font-medium text-gray-900">{{ $opportunity->responsible_person['name'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-lucide-briefcase class="h-4 w-4 text-gray-600" />
                                        <span class="text-gray-700">{{ $opportunity->responsible_person['position'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-lucide-phone class="h-4 w-4 text-gray-600" />
                                        <span class="text-gray-700">{{ $opportunity->responsible_person['contact'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
