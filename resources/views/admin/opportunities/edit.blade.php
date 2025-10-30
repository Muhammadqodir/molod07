@extends('layouts.sidebar-layout')

@section('title', 'Редактировать возможность')

@section('content')
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.opportunities.index') }}"
           class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">
            <x-lucide-arrow-left class="h-5 w-5" />
        </a>
        <h1 class="text-3xl">Редактировать возможность</h1>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.opportunities.update', $opportunity) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="ministry_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Министерство <span class="text-red-500">*</span>
                    </label>
                    <select name="ministry_id"
                            id="ministry_id"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Выберите министерство</option>
                        @foreach ($ministries as $ministry)
                            <option value="{{ $ministry->id }}" {{ old('ministry_id', $opportunity->ministry_id) == $ministry->id ? 'selected' : '' }}>
                                {{ $ministry->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('ministry_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="program_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Название программы <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="program_name"
                           name="program_name"
                           value="{{ old('program_name', $opportunity->program_name) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('program_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="participation_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                        Условия участия <span class="text-red-500">*</span>
                    </label>
                    <textarea id="participation_conditions"
                              name="participation_conditions"
                              rows="4"
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('participation_conditions', $opportunity->participation_conditions) }}</textarea>
                    @error('participation_conditions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="implementation_period" class="block text-sm font-medium text-gray-700 mb-2">
                        Сроки реализации программы <span class="text-red-500">*</span>
                    </label>
                    <textarea id="implementation_period"
                              name="implementation_period"
                              rows="3"
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('implementation_period', $opportunity->implementation_period) }}</textarea>
                    @error('implementation_period')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="required_documents" class="block text-sm font-medium text-gray-700 mb-2">
                        Перечень необходимых документов <span class="text-red-500">*</span>
                    </label>
                    <textarea id="required_documents"
                              name="required_documents"
                              rows="4"
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('required_documents', $opportunity->required_documents) }}</textarea>
                    @error('required_documents')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Legal Documents -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Нормативно-правовые документы
                    </label>
                    <div id="legal-documents-container">
                        @php
                            $legalDocs = old('legal_documents', $opportunity->legal_documents ?? []);
                        @endphp
                        @forelse($legalDocs as $index => $document)
                            <div class="legal-document-item grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                <input type="text"
                                       name="legal_documents[{{ $index }}][title]"
                                       placeholder="Название документа"
                                       value="{{ $document['title'] ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div class="flex gap-2">
                                    <input type="url"
                                           name="legal_documents[{{ $index }}][link]"
                                           placeholder="Ссылка на документ"
                                           value="{{ $document['link'] ?? '' }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @if($loop->index > 0)
                                        <button type="button"
                                                onclick="removeLegalDocument(this)"
                                                class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
                                            <x-lucide-trash class="h-4 w-4" />
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="legal-document-item grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                <input type="text"
                                       name="legal_documents[0][title]"
                                       placeholder="Название документа"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <input type="url"
                                       name="legal_documents[0][link]"
                                       placeholder="Ссылка на документ"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        @endforelse
                    </div>
                    <button type="button"
                            onclick="addLegalDocument()"
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm text-blue-600 hover:text-blue-800 transition">
                        <x-lucide-plus class="h-4 w-4" />
                        Добавить документ
                    </button>
                </div>

                <!-- Responsible Person -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ответственное лицо <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <input type="text"
                                   name="responsible_person[name]"
                                   placeholder="ФИО"
                                   value="{{ old('responsible_person.name', $opportunity->responsible_person['name'] ?? '') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('responsible_person.name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="text"
                                   name="responsible_person[position]"
                                   placeholder="Должность"
                                   value="{{ old('responsible_person.position', $opportunity->responsible_person['position'] ?? '') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('responsible_person.position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="text"
                                   name="responsible_person[contact]"
                                   placeholder="Контакт"
                                   value="{{ old('responsible_person.contact', $opportunity->responsible_person['contact'] ?? '') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('responsible_person.contact')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8">
                <a href="{{ route('admin.opportunities.index') }}"
                   class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
                    Отмена
                </a>
                <x-button type="submit">
                    Сохранить
                </x-button>
            </div>
        </form>
    </div>

    <script>
        let legalDocumentIndex = {{ count($legalDocs ?? []) }};

        function addLegalDocument() {
            const container = document.getElementById('legal-documents-container');
            const newItem = document.createElement('div');
            newItem.className = 'legal-document-item grid grid-cols-1 md:grid-cols-2 gap-3 mb-3';
            newItem.innerHTML = `
                <input type="text"
                       name="legal_documents[${legalDocumentIndex}][title]"
                       placeholder="Название документа"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="flex gap-2">
                    <input type="url"
                           name="legal_documents[${legalDocumentIndex}][link]"
                           placeholder="Ссылка на документ"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button"
                            onclick="removeLegalDocument(this)"
                            class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
                        <x-lucide-trash class="h-4 w-4" />
                    </button>
                </div>
            `;
            container.appendChild(newItem);
            legalDocumentIndex++;
        }

        function removeLegalDocument(button) {
            button.closest('.legal-document-item').remove();
        }
    </script>
@endsection
