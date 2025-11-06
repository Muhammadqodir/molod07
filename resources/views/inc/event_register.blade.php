{{-- Registration Dialog --}}
<div x-show="showRegistrationDialog" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true" style="display: none; z-index: 99999999999;">

    {{-- Background overlay --}}
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showRegistrationDialog = false">
    </div>

    {{-- Modal panel --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="showRegistrationDialog" x-transition:enter="transition ease-out duration-300"
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
                    <h1 class="text-xl font-semibold text-gray-900">Регистрация на мероприятие</h1>
                    <button @click="showRegistrationDialog = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <x-lucide-x class="w-6 h-6" />
                    </button>
                </div>

                {{-- Check if user is authenticated and is youth --}}
                @auth
                    @if(auth()->user()->role === 'youth')
                        {{-- Registration form --}}
                        <form action="{{ route('event.register', $event->id) }}" method="POST" x-data="eventRegistration()"
                            class="space-y-6">
                            @csrf

                            {{-- Role selection --}}
                            <div class="space-y-3">
                                <label class="text-sm font-medium text-gray-900">Выберите роль *</label>
                                @php
                                    $roleOptions = collect($roles)
                                        ->map(function ($role, $index) {
                                            return [
                                                'value' => $index,
                                                'label' => $role['title'] ?? 'Роль ' . ($index + 1),
                                                'description' => $role['description'] ?? '',
                                                'points' => $role['points'] ?? 0,
                                            ];
                                        })
                                        ->toArray();
                                @endphp

                                <x-multi-choice name="role" :options="$roleOptions" :value="old('role') ? [old('role')] : []" :multiple="false"
                                    title="Роль" />

                                {{-- Show role details --}}
                                <template x-if="selectedRole !== null && roles[selectedRole]">
                                    <div class="mt-3 p-4 bg-gray-50 rounded-lg">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900 mb-2" x-text="roles[selectedRole].title">
                                            </div>
                                            <div class="text-gray-600 mb-2" x-text="roles[selectedRole].description"></div>
                                            <div class="flex items-center gap-2">
                                                <x-lucide-coins class="w-4 h-4 text-primary" />
                                                <span class="text-primary font-medium"
                                                    x-text="roles[selectedRole].points + ' баллов'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Contact information notice --}}
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <x-lucide-info class="w-5 h-5 text-blue-600 mt-0.5" />
                                    <div class="text-sm">
                                        <div class="font-medium text-blue-900 mb-1">Обратите внимание</div>
                                        <div class="text-blue-700">
                                            После подачи заявки с вами свяжется куратор мероприятия для уточнения деталей
                                            участия.
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
                                    <button type="button" @click="showRegistrationDialog = false"
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                        Отмена
                                    </button>
                                    <x-button type="submit" x-bind:disabled="selectedRole === null"
                                        x-bind:class="selectedRole === null ? 'opacity-50 cursor-not-allowed' : ''">
                                        Подать заявку
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
                                    Регистрация на мероприятия доступна только для молодежи.</strong>
                                </p>
                            </div>
                            <button type="button" @click="showRegistrationDialog = false"
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
                                Для подачи заявки на участие в мероприятии необходимо войти в систему
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

                @if(auth()->check() && auth()->user()->role === 'youth')
                    <script>
                        function eventRegistration() {
                            return {
                                roles: @json($roles),
                                selectedRole: null,

                                init() {
                                    // Check for role selection changes every 100ms
                                    const checkRole = () => {
                                        const roleInput = this.$el.querySelector('input[name="role"]');
                                        if (roleInput) {
                                            const newValue = roleInput.value;
                                            const roleIndex = newValue !== '' ? parseInt(newValue) : null;
                                            if (this.selectedRole !== roleIndex) {
                                                this.selectedRole = roleIndex;
                                            }
                                        }
                                    };

                                    // Check immediately and then periodically
                                    checkRole();
                                    setInterval(checkRole, 100);
                                }
                            }
                        }
                    </script>
                @endif
            </div>
        </div>
    </div>
</div>
