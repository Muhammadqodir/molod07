@extends('layouts.app')

@section('title', $grant->title)

@section('content')
    @php
        use Carbon\Carbon;

        $docs = (array) ($grant->docs ?? []);
        $conditions = explode("\n", $grant->conditions ?? '');
        $requirements = explode("\n", $grant->requirements ?? '');
        $rewards = explode("\n", $grant->reward ?? '');

        $dateStr = function ($date) {
            if (!$date) {
                return '';
            }
            return Carbon::parse($date)->translatedFormat('d.m.Y');
        };
    @endphp

    <section class="bg-accentbg mt-[-80px] pt-[80px]">
        <div class="max-w-6xl mx-auto py-6 space-y-8" x-data="{ tab: 'info', showGrantApply: false }">

            {{-- Top: cover + header --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-4 md:p-6">
                    {{-- Cover --}}
                    <div class="md:col-span-4">
                        <div class="aspect-[4/3] bg-gray-100 rounded-2xl overflow-hidden">
                            @if ($grant->cover)
                                <img src="{{ asset($grant->cover) }}" alt="cover" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>

                    {{-- Main info --}}
                    <div class="md:col-span-8 flex flex-col gap-3">
                        <div class="flex items-center gap-2 flex-wrap text-sm">
                            @if ($grant->category)
                                <span
                                    class="inline-flex items-center gap-2 bg-[#E2ECFF] text-gray-700 px-3 py-1 rounded-xl">
                                    <?php $categories = config('grants.categories'); ?>
                                    @foreach ($categories as $item)
                                        @if ($item['label'] == $grant->category)
                                            <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-4 h-4" />
                                        @endif
                                    @endforeach
                                    {{ $grant->category }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-2 bg-[#ECFFB5] text-gray-700 px-3 py-1 rounded-xl">
                                <x-lucide-award class="w-4 h-4" />
                                Грант
                            </span>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-semibold leading-tight">
                            {{ $grant->title }}
                        </h1>

                        @if ($grant->short_description)
                            <p class="text-gray-600 text-sm md:text-base">
                                {{ $grant->short_description }}
                            </p>
                        @endif

                        <div class="mt-auto flex items-center justify-between gap-3">
                            <x-button x-on:click="showGrantApply = true">
                                Подать заявку
                            </x-button>

                            <div class="flex items-center gap-2 text-gray-500">
                                <button type="button" class="p-2 hover:bg-gray-100 rounded-xl" title="Поделиться">
                                    <x-lucide-share-2 class="w-5 h-5" />
                                </button>
                                <button type="button" class="p-2 hover:bg-gray-100 rounded-xl" title="В избранное">
                                    <x-lucide-heart class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section tabs --}}
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">

                <h2 class="text-lg font-semibold mb-2">Информация о гранте</h2>

                <div class="border-b">
                    <nav class="flex gap-6 text-sm">
                        <button class="py-4 border-b-2"
                            :class="tab === 'info' ? 'border-primary text-primary' : 'border-transparent text-gray-500'"
                            @click="tab='info'">
                            Подробная информация
                        </button>
                        <button class="py-4 border-b-2"
                            :class="tab === 'docs' ? 'border-primary text-primary' : 'border-transparent text-gray-500'"
                            @click="tab='docs'">
                            Документы
                        </button>
                    </nav>
                </div>

                {{-- Tab: Info --}}
                <div x-show="tab==='info'" class="space-y-10 mt-6">

                    {{-- Organizer / contact / dates / description / socials --}}
                    <section class="space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-8">
                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Организатор</div>
                                <div class="flex items-center gap-2">
                                    <x-profile-pic :user="$grant->partner" size="w-12 h-12" />
                                    <div class="text-sm text-primary">
                                        {{ $grant->partner->getFullName() ?? 'Организация' }}
                                        <x-lucide-badge-check class="inline w-4 h-4 text-primary" />
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Место подачи заявок</div>
                                <div class="text-sm text-gray-700 flex items-start gap-2">
                                    <x-lucide-map-pin class="w-4 h-4 mt-0.5 text-gray-500" />
                                    <div>
                                        {{ $grant->settlement ?? '—' }}, {{ $grant->address ?? '—' }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Дедлайн подачи заявок</div>
                                <div class="text-sm text-gray-700 flex items-start gap-2">
                                    <x-lucide-calendar-x class="w-4 h-4 mt-0.5 text-gray-500" />
                                    <div>
                                        {{ $dateStr($grant->deadline) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($grant->description)
                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Описание</div>
                                <p class="text-sm text-gray-700 leading-relaxed">{!! $grant->description !!}</p>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <div class="text-xs uppercase text-gray-400">Грант в соцсетях</div>
                            <div class="flex items-center gap-3 text-gray-600">
                                @if ($grant->vk)
                                    <x-icon-button icon="vk" />
                                @endif
                                @if ($grant->telegram)
                                    <x-icon-button icon="telega" />
                                @endif
                                @if ($grant->web)
                                    <a href="{{ $grant->web }}" target="_blank">
                                        <x-icon-button icon="globe" />
                                    </a>
                                @endif
                            </div>
                        </div>
                    </section>

                </div>

                {{-- Tab: Documents --}}
                <div x-show="tab==='docs'" class="space-y-8 mt-6">
                    {{-- Docs --}}
                    @if ($docs)
                        <section class="space-y-3">
                            <h3 class="text-lg">Документы</h3>
                            <div class="space-y-2">
                                @foreach ($docs as $d)
                                    @php
                                        $doc = is_array($d) ? $d : ['url' => $d, 'name' => basename($d)];
                                        // Fix the file path for filesize calculation
                                        $filePath = str_starts_with($doc['url'], 'storage/')
                                            ? storage_path('app/public/' . str_replace('storage/', '', $doc['url']))
                                            : storage_path('app/' . $doc['url']);
                                        $sizeBytes = file_exists($filePath) ? filesize($filePath) : 0;
                                        $doc['size'] =
                                            $sizeBytes > 0 ? round($sizeBytes / 1024 / 1024, 2) . ' МБ' : 'Неизвестно';
                                    @endphp
                                    <div class="flex items-center justify-between border-b pb-2">
                                        <div class="flex items-center gap-3">
                                            <x-lucide-file-text class="w-5 h-5 text-gray-500" />
                                            <div>
                                                <div class="text-sm">{{ $doc['name'] ?? basename($doc['url']) }}</div>
                                                <div class="text-xs text-gray-500">{{ $doc['size'] }}</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-4 text-primary">
                                            <a href="{{ asset($doc['url']) }}" download
                                                class="flex items-center gap-1 text-blue-600">
                                                <x-lucide-download class="w-4 h-4" /> Скачать
                                            </a>
                                            <a href="{{ asset($doc['preview'] ?? $doc['url']) }}" target="_blank"
                                                class="flex items-center gap-1 text-blue-600">
                                                <x-lucide-eye class="w-4 h-4" /> Посмотреть
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            <x-lucide-file-x class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                            <p>Документы не добавлены</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Grant conditions and requirements --}}
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">
                <section class="space-y-6">
                    <h2 class="text-lg font-semibold">Условия участия</h2>

                    {{-- Conditions --}}
                    @if ($grant->conditions)
                        <div class="space-y-3">
                            <h3 class="text-base font-medium">Условия участия</h3>
                            <div class="space-y-2">
                                @foreach ($conditions as $condition)
                                    @if (trim($condition))
                                        <div class="flex items-start gap-2 text-sm text-gray-700">
                                            <x-lucide-check class="w-4 h-4 mt-0.5 text-green-500 flex-shrink-0" />
                                            <span>{{ trim($condition) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Requirements --}}
                    @if ($grant->requirements)
                        <div class="space-y-3">
                            <h3 class="text-base font-medium">Требования к участникам</h3>
                            <div class="space-y-2">
                                @foreach ($requirements as $requirement)
                                    @if (trim($requirement))
                                        <div class="flex items-start gap-2 text-sm text-gray-700">
                                            <x-lucide-alert-circle class="w-4 h-4 mt-0.5 text-blue-500 flex-shrink-0" />
                                            <span>{{ trim($requirement) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Rewards --}}
                    @if ($grant->reward)
                        <div class="space-y-3">
                            <h3 class="text-base font-medium">Награда/Поощрение</h3>
                            <div class="space-y-2">
                                @foreach ($rewards as $reward)
                                    @if (trim($reward))
                                        <div class="flex items-start gap-2 text-sm text-gray-700">
                                            <x-lucide-gift class="w-4 h-4 mt-0.5 text-yellow-500 flex-shrink-0" />
                                            <span>{{ trim($reward) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <x-button class="mt-4" x-on:click="showGrantApply = true">Подать заявку</x-button>
                </section>
            </div>

            {{-- Диалог подачи заявки на грант --}}
            <div x-show="showGrantApply" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true"
                style="display: none; z-index: 99999999999;">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showGrantApply = false">
                </div>
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div x-show="showGrantApply" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                        <div class="bg-white p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h1 class="text-xl font-semibold text-gray-900">Подача заявки на грант</h1>
                                <button @click="showGrantApply = false"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <x-lucide-x class="w-6 h-6" />
                                </button>
                            </div>
                            @auth
                                @if (auth()->user()->role === 'youth')
                                    <form method="POST" action="{{ route('grant.apply', $grant->id) }}" class="space-y-6">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Ваше имя *</label>
                                            <input type="text" name="name" class="w-full border rounded px-3 py-2"
                                                required value="{{ auth()->user()->getFullName() ?? '' }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Email *</label>
                                            <input type="email" name="email" class="w-full border rounded px-3 py-2"
                                                required value="{{ auth()->user()->email ?? '' }}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Комментарий</label>
                                            <textarea name="comment" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                                        </div>
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-2">
                                            <div class="flex items-start gap-3">
                                                <x-lucide-info class="w-5 h-5 text-blue-600 mt-0.5" />
                                                <div class="text-sm">
                                                    <div class="font-medium text-blue-900 mb-1">Обратите внимание</div>
                                                    <div class="text-blue-700">
                                                        После подачи заявки с вами свяжется организатор гранта для уточнения
                                                        деталей.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between pt-4 border-t">
                                            <button type="button" @click="showGrantApply = false"
                                                class="px-4 py-2 text-gray-600 hover:text-gray-800">Отмена</button>
                                            <x-button type="submit" class="w-full">Отправить заявку</x-button>
                                        </div>
                                    </form>
                                @else
                                    <div class="text-center py-8">
                                        <x-lucide-user-x class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Доступ ограничен</h3>
                                        <p class="text-gray-600 mb-6">Подача заявки доступна только для молодежи.</p>
                                        <button type="button" @click="showGrantApply = false"
                                            class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Закрыть</button>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <x-lucide-user-circle class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Требуется авторизация</h3>
                                    <p class="text-gray-600 mb-6">Для подачи заявки на грант необходимо войти в систему</p>
                                    <div class="flex items-center justify-center gap-4">
                                        <a href="{{ route('login') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">Войти
                                            в систему</a>
                                        <a href="{{ route('youth.reg') }}"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Зарегистрироваться</a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
