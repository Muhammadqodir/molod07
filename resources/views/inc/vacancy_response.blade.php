{{-- Vacancy Response Dialog --}}
<div x-show="showVacancyResponseDialog" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true" style="display: none; z-index: 99999999999;">

    {{-- Background overlay --}}
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showVacancyResponseDialog = false">
    </div>

    {{-- Modal panel --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="showVacancyResponseDialog" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">

            {{-- Modal content --}}
            <div class="bg-white p-6">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-gray-900">Отклик на вакансию</h1>
                    <button @click="showVacancyResponseDialog = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <x-lucide-x class="w-6 h-6" />
                    </button>
                </div>

                {{-- Check if user is authenticated and is youth --}}
                @auth
                    @if(auth()->user()->role === 'youth')
                        {{-- Response form --}}
                        <form action="{{ route('vacancy.respond', $vacancy->id) }}" method="POST" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf

                            {{-- Resume file upload --}}
                            <div class="space-y-3">
                                <label class="text-sm font-medium text-gray-900">Резюме *</label>
                                <input type="file" name="resume" accept=".pdf,.doc,.docx" required
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary/90">
                                <p class="text-xs text-gray-500">Поддерживаемые форматы: PDF, DOC, DOCX. Максимальный размер: 5MB</p>
                            </div>

                            {{-- Cover letter --}}
                            <div class="space-y-3">
                                <label class="text-sm font-medium text-gray-900">Сопроводительное письмо *</label>
                                <textarea name="cover_letter" required rows="4" placeholder="Расскажите, почему вы подходите для этой позиции..."
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"></textarea>
                            </div>

                            {{-- Preferred contact method --}}
                            <div class="space-y-3">
                                <label class="text-sm font-medium text-gray-900">Предпочтительный способ связи *</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="prefered_contact" value="phone" required 
                                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Телефонный звонок</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="prefered_contact" value="email" required
                                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Электронная почта</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="prefered_contact" value="telegram" required
                                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Telegram</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Contact information notice --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <x-lucide-info class="w-5 h-5 text-blue-600 mt-0.5" />
                                    <div class="text-sm">
                                        <div class="font-medium text-blue-900 mb-1">Обратите внимание</div>
                                        <div class="text-blue-700">
                                            После отправки отклика с вами свяжется работодатель выбранным способом связи.
                                            Убедитесь, что ваши контактные данные актуальны в профиле.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit button --}}
                            <div class="flex items-center justify-between pt-4 border-t">
                                <div class="text-sm text-gray-500">
                                    * Обязательные поля
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="showVacancyResponseDialog = false"
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                        Отмена
                                    </button>
                                    <x-button type="submit">
                                        Отправить отклик
                                    </x-button>
                                </div>
                            </div>
                        </form>
                    @else
                        {{-- User is authenticated but not youth --}}
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <x-lucide-user-x class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Доступ ограничен</h3>
                                <p class="text-gray-600 mb-6">
                                    Отклики на вакансии доступны только для молодежи.
                                </p>
                            </div>
                            <button type="button" @click="showVacancyResponseDialog = false"
                                class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                Закрыть
                            </button>
                        </div>
                    @endif
                @else
                    {{-- User not authenticated --}}
                    <div class="text-center py-8">
                        <div class="mb-4">
                            <x-lucide-user-circle class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Требуется авторизация</h3>
                            <p class="text-gray-600 mb-6">
                                Для отклика на вакансию необходимо войти в систему
                            </p>
                        </div>
                        <div class="flex items-center justify-center gap-4">
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                                Войти в систему
                            </a>
                            <a href="{{ route('youth.reg') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Зарегистрироваться
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
