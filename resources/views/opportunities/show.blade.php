@extends('layouts.app')

@section('title', $opportunity->program_name)

@section('content')

<section class="bg-accentbg mt-[-80px] pt-[80px] pb-[80px] min-h-[calc(100vh-200px)]">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="mt-12">
            <!-- Breadcrumbs -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('main') }}" class="hover:text-blue-600 transition">Главная</a></li>
                    <li><x-lucide-chevron-right class="h-4 w-4" /></li>
                    <li><a href="{{ route('opportunities.index') }}" class="hover:text-blue-600 transition">Возможности</a></li>
                    <li><x-lucide-chevron-right class="h-4 w-4" /></li>
                    <li class="text-gray-800 font-medium">{{ Str::limit($opportunity->program_name, 50) }}</li>
                </ol>
            </nav>

            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $opportunity->ministry->title }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $opportunity->program_name }}</h1>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main content -->
                    <div class="lg:col-span-2 space-y-8">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <x-lucide-users class="h-5 w-5 text-blue-600" />
                                Условия участия
                            </h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                {!! nl2br(e($opportunity->participation_conditions)) !!}
                            </div>
                        </div>

                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <x-lucide-calendar class="h-5 w-5 text-blue-600" />
                                Сроки реализации программы
                            </h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                {!! nl2br(e($opportunity->implementation_period)) !!}
                            </div>
                        </div>

                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <x-lucide-file-text class="h-5 w-5 text-blue-600" />
                                Необходимые документы
                            </h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                {!! nl2br(e($opportunity->required_documents)) !!}
                            </div>
                        </div>

                        @if($opportunity->legal_documents && count($opportunity->legal_documents) > 0)
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                    <x-lucide-scale class="h-5 w-5 text-blue-600" />
                                    Нормативно-правовые документы
                                </h2>
                                <div class="space-y-3">
                                    @foreach($opportunity->legal_documents as $document)
                                        @if(!empty($document['title']) && !empty($document['link']))
                                            <a href="{{ $document['link'] }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition-colors group">
                                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                                    <x-lucide-external-link class="h-5 w-5 text-blue-600" />
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-800 group-hover:text-blue-800">{{ $document['title'] }}</div>
                                                    <div class="text-sm text-gray-500">Открыть документ</div>
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        @if($opportunity->responsible_person)
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                    <x-lucide-user class="h-5 w-5 text-blue-600" />
                                    Ответственное лицо
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center">
                                            <x-lucide-user class="h-4 w-4 text-blue-700" />
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $opportunity->responsible_person['name'] ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-600">{{ $opportunity->responsible_person['position'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                                            <x-lucide-phone class="h-4 w-4 text-green-700" />
                                        </div>
                                        <div class="text-gray-700">{{ $opportunity->responsible_person['contact'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Back to list -->
                        <div class="mt-6">
                            <a href="{{ route('opportunities.index') }}"
                               class="inline-flex items-center gap-2 w-full px-4 py-3 text-center border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                <x-lucide-arrow-left class="h-4 w-4" />
                                Все возможности
                            </a>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('opportunities.by-ministry', $opportunity->ministry) }}"
                               class="inline-flex items-center gap-2 w-full px-4 py-3 text-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <x-lucide-building class="h-4 w-4" />
                                Другие программы {{ $opportunity->ministry->title }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
