@extends('layouts.app')

@section('title', $event->title)

@section('content')
    @php
        use Carbon\Carbon;

        $roles = json_decode($event->roles ?? '[]', true);
        $docs = (array) ($event->docs ?? []);
        $images = (array) ($event->images ?? []);
        $videos = (array) ($event->videos ?? []);

        $periodStr = function ($start, $end) {
            if (!$start) {
                return '';
            }
            $s = Carbon::parse($start)->translatedFormat('d.m.Y');
            if ($end && $end !== $start) {
                $e = Carbon::parse($end)->translatedFormat('d.m.Y');
                return "$s — $e";
            }
            return $s;
        };
    @endphp

    <section class="bg-accentbg mt-[-80px] pt-[80px]">
        <div class="max-w-6xl mx-auto py-6 space-y-8" x-data="{ tab: 'info', roleIdx: 0 }">

            {{-- Top: cover + header --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-4 md:p-6">
                    {{-- Cover --}}
                    <div class="md:col-span-4">
                        <div class="aspect-[4/3] bg-gray-100 rounded-2xl overflow-hidden">
                            @if ($event->cover)
                                <img src="{{ asset($event->cover) }}" alt="cover" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>

                    {{-- Main info --}}
                    <div class="md:col-span-8 flex flex-col gap-3">
                        <div class="flex items-center gap-2 flex-wrap text-sm">
                            @if ($event->type)
                                <span
                                    class="inline-flex items-center gap-2 bg-[#EDE2FF] text-gray-700 px-3 py-1 rounded-xl">
                                    {{ $event->type }}
                                </span>
                            @endif
                            @if ($event->category)
                                <span
                                    class="inline-flex items-center gap-2 bg-[#E2ECFF] text-gray-700 px-3 py-1 rounded-xl">
                                    <?php $categories = config('events.categories'); ?>
                                    @foreach ($categories as $item)
                                        @if ($item['label'] == $event->category)
                                            <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-4 h-4" />
                                        @endif
                                    @endforeach
                                    {{ $event->category }}
                                </span>
                            @endif
                            @php
                                $sumPoints = collect($roles)->sum(fn($r) => (int) ($r['points'] ?? 0));
                            @endphp
                            @if ($sumPoints)
                                <span
                                    class="inline-flex items-center gap-2 bg-[#ECFFB5] text-gray-700 px-3 py-1 rounded-xl">
                                    <x-lucide-coins class="w-4 h-4" />
                                    {{ $sumPoints }} баллов
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl md:text-3xl font-semibold leading-tight">
                            {{ $event->title }}
                        </h1>

                        @if ($event->short_description)
                            <p class="text-gray-600 text-sm md:text-base">
                                {{ $event->short_description }}
                            </p>
                        @endif

                        <div class="mt-auto flex items-center justify-between gap-3">
                            <x-button>
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

                <h2 class="text-lg font-semibold mb-2">Информация о мероприятии</h2>

                <div class="border-b">
                    <nav class="flex gap-6 text-sm">
                        <button class="py-4 border-b-2"
                            :class="tab === 'info' ? 'border-primary text-primary' : 'border-transparent text-gray-500'"
                            @click="tab='info'">
                            Подробная информация
                        </button>
                        <button class="py-4 border-b-2"
                            :class="tab === 'media' ? 'border-primary text-primary' : 'border-transparent text-gray-500'"
                            @click="tab='media'">
                            Медиафайлы
                        </button>
                    </nav>
                </div>

                {{-- Tab: Info --}}
                <div x-show="tab==='info'" class="space-y-10 mt-6">

                    {{-- Cover + header --}}

                    {{-- Organizer / contact / dates / description / socials --}}
                    <section class="space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-8">
                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Организатор</div>
                                <div class="flex items-center gap-2">
                                    <x-profile-pic :user="$event->partner" size="w-12 h-12" />
                                    <div class="text-sm text-primary">
                                        {{-- сюда можно вывести партнёра/организацию при наличии --}}
                                        {{ $event->partner->getFullName() ?? 'Организация' }}
                                        {{-- @if ($event->partnersProfile->verified ?? false) --}}
                                        <x-lucide-badge-check class="inline w-4 h-4 text-primary" />
                                        {{-- @endif --}}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Контактное лицо</div>
                                <div class="flex items-center gap-2">
                                    @if ($event->supervisor)
                                        <x-profile-pic :user="$event->supervisor" size="w-12 h-12" />
                                        <div class="text-sm">
                                            {{ $event->supervisor->getFullName() }}
                                            <div class="text-gray-500 text-xs">куратор мероприятия</div>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-xl object-cover" alt="">
                                            <x-lucide-user />
                                        </div>
                                        <div class="text-sm">
                                            {{ $event->supervisor_name }} {{ $event->supervisor_l_name }}
                                            <div class="text-gray-500 text-xs">куратор мероприятия</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Дата и место проведения</div>
                                <div class="text-sm text-gray-700 flex items-start gap-2">
                                    <x-lucide-map-pin class="w-4 h-4 mt-0.5 text-gray-500" />
                                    <div>
                                        {{ $event->settlement ?? '—' }}, {{ $event->address ?? '—' }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-700 flex items-start gap-2">
                                    <x-lucide-calendar-days class="w-4 h-4 mt-0.5 text-gray-500" />
                                    <div>
                                        {{ $periodStr($event->start, $event->end) }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Набор участников</div>
                                <div class="text-sm text-gray-700 flex items-start gap-2">
                                    <x-lucide-calendar-days class="w-4 h-4 mt-0.5 text-gray-500" />
                                    <div>
                                        {{-- если есть отдельные даты отбора — берём из первой роли --}}
                                        @php
                                            $selS = data_get($roles, '0.selection_start');
                                            $selE = data_get($roles, '0.selection_end');
                                        @endphp
                                        {{ $selS ? $periodStr($selS, $selE) : '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($event->description)
                            <div class="space-y-2">
                                <div class="text-xs uppercase text-gray-400">Описание</div>
                                <p class="text-sm text-gray-700 leading-relaxed">{!! $event->description !!}</p>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <div class="text-xs uppercase text-gray-400">Мероприятие в соцсетях</div>
                            <div class="flex items-center gap-3 text-gray-600">
                                @if ($event->vk)
                                    <x-icon-button icon="vk" />
                                @endif
                                @if ($event->telegram)
                                    <x-icon-button icon="telega" />
                                @endif
                                @if ($event->web)
                                    <a href="{{ $event->web }}" target="_blank">
                                        <x-icon-button icon="globe" />
                                    </a>
                                @endif
                            </div>
                        </div>
                    </section>

                </div>

                {{-- Tab: Media --}}
                <div x-show="tab==='media'" class="space-y-8">
                    {{-- Images --}}
                    @if ($images)
                        <section class="space-y-3">
                            <h3 class="text-lg">Фотографии</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach ($images as $img)
                                    <a href="{{ asset((is_array($img) ? $img['url'] ?? '#' : $img)) }}"
                                        target="_blank" class="block aspect-[4/3] rounded-xl overflow-hidden bg-gray-100">
                                        <img src="{{ asset((is_array($img) ? $img['url'] ?? '#' : $img)) }}"
                                            class="w-full h-full object-cover" alt="">
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- Videos --}}
                    @if ($videos)
                        <section class="space-y-3">
                            <h3 class="text-lg">Видео</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($videos as $vid)
                                    @php $src = is_array($vid) ? ($vid['url'] ?? '') : $vid; @endphp
                                    <div class="aspect-video rounded-xl overflow-hidden bg-black">
                                        {{-- если это внешний URL на youtube — можно iframe, иначе video --}}
                                        @if (Str::contains($src, ['youtube.com', 'youtu.be']))
                                            <iframe src="{{ $src }}" class="w-full h-full"
                                                allowfullscreen></iframe>
                                        @else
                                            <video src="{{ $src }}" controls class="w-full h-full"></video>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif


                    {{-- Docs --}}
                    @if ($docs)
                        <section class="space-y-3 mt-6">
                            <h3 class="text-lg">Документы</h3>
                            <div class="space-y-2">
                                @foreach ($docs as $d)
                                    @php
                                        $doc = is_array($d)
                                            ? $d
                                            : [
                                                'name' => basename($d),
                                                'url' => asset( $d),
                                                'type' => pathinfo($d, PATHINFO_EXTENSION),
                                            ];
                                        // Fix the file path for filesize calculation
                                        $filePath = str_starts_with($d, 'storage/')
                                            ? storage_path('app/public/' . substr($d, 8))
                                            : storage_path('app/' . $d);
                                        $sizeBytes = file_exists($filePath) ? filesize($filePath) : 0;
                                        $doc['size'] = $sizeBytes > 0 ? round($sizeBytes / 1024 / 1024, 2) . ' МБ' : 'Неизвестно';
                                    @endphp
                                    <div class="flex items-center justify-between border-b pb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-3 bg-primary p-2 rounded-lg">
                                                <x-lucide-file-text class="w-5 h-5 text-white" />
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-800">{{ $doc['name'] }}</div>
                                                <div class="text-sm text-gray-500">{{ $doc['size'] }},
                                                    {{ strtoupper($doc['type']) }}</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-4 text-primary">
                                            <a href="{{ $doc['url'] }}" download
                                                class="flex items-center gap-1 text-blue-600">
                                                <x-lucide-download class="w-4 h-4" /> Скачать
                                            </a>
                                            <a href="{{ $doc['preview'] ?? $doc['url'] }}" target="_blank"
                                                class="flex items-center gap-1 text-blue-600">
                                                <x-lucide-eye class="w-4 h-4" /> Посмотреть
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>
            </div>

            {{-- Section tabs --}}
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">

                {{-- Roles (Условия участия) --}}
                @if ($roles && count($roles))
                    <section class="space-y-4" x-data>
                        <h2 class="text-lg font-semibold">Условия участия</h2>

                        {{-- Tabs by roles --}}
                        <div class="flex items-center gap-4 border-b">
                            @foreach ($roles as $i => $r)
                                <button class="py-3 text-sm border-b-2"
                                    :class="roleIdx === {{ $i }} ? 'border-primary text-primary' :
                                        'border-transparent text-gray-500'"
                                    @click="roleIdx={{ $i }}">
                                    {{ $r['title'] ?? 'Роль ' . ($i + 1) }}
                                </button>
                            @endforeach
                        </div>

                        @foreach ($roles as $i => $r)
                            <div x-show="roleIdx==={{ $i }}" class="space-y-4">
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">Период отбора:</span>
                                    {{ $periodStr($r['selection_start'] ?? null, $r['selection_end'] ?? null) ?: '—' }}
                                </div>

                                @if (!empty($r['description']))
                                    <div>
                                        <div class="text-sm font-medium mb-1">Описание</div>
                                        <p class="text-sm text-gray-700">{{ $r['description'] }}</p>
                                    </div>
                                @endif

                                @if (!empty($r['task']))
                                    <div>
                                        <div class="text-sm font-medium mb-1">Задачи</div>
                                        <p class="text-sm text-gray-700">{{ $r['task'] }}</p>
                                    </div>
                                @endif

                                {{-- Shifts --}}
                                @if (!empty($r['shifts']) && is_array($r['shifts']))
                                    <div class="space-y-2">
                                        <div class="text-sm font-medium">Занятость</div>
                                        <div class="space-y-1 text-sm text-gray-700">
                                            @foreach ($r['shifts'] as $idx => $s)
                                                <div class="flex items-center gap-4">
                                                    <span class="text-gray-500">{{ $idx + 1 }} смена</span>
                                                    <span>
                                                        {{ $periodStr($s['date_start'] ?? null, $s['date_end'] ?? null) ?: '—' }},
                                                        {{ $s['time_start'] ?? '—' }} —
                                                        {{ $s['time_end'] ?? '—' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Requirements --}}
                                @if (!empty($r['requirements']))
                                    @php $req = is_array($r['requirements']) ? $r['requirements'] : explode(',', (string)$r['requirements']); @endphp
                                    <div class="space-y-2">
                                        <div class="text-sm font-medium">Требования</div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($req as $q)
                                                <span
                                                    class="px-3 py-1 rounded-xl bg-gray-100 text-gray-700 text-sm">{{ trim($q) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Benefits --}}
                                @if (!empty($r['benefits']))
                                    @php $ben = is_array($r['benefits']) ? $r['benefits'] : explode(',', (string)$r['benefits']); @endphp
                                    <div class="space-y-2">
                                        <div class="text-sm font-medium">Предлагаемые условия</div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($ben as $b)
                                                <span
                                                    class="px-3 py-1 rounded-xl bg-blue-50 text-primary text-sm">{{ trim($b) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <x-button class="mt-2">Подать заявку</x-button>
                            </div>
                        @endforeach
                    </section>
                @endif
            </div>
        </div>

    </section>
@endsection
