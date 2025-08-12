@props([
    // имена инпутов
    'docsName' => 'docs',
    'imagesName' => 'images',
    'videosName' => 'videos',

    // доп. тексты
    'label' => 'Добавление медиафайлов',

    // лимиты (кол-во и размер в МБ)
    'maxDocs' => 5,
    'maxImages' => 6,
    'maxVideos' => 1,
    'maxDocMb' => 5,
    'maxImgMb' => 2,
    'maxVidMb' => 100,

    // форматы
    'docAccept' => ['application/pdf'],
    'imgAccept' => ['image/png', 'image/jpeg'],
    'vidAccept' => ['video/mp4', 'video/quicktime'],
])

@php
    $docAcceptAttr = implode(',', $docAccept);
    $imgAcceptAttr = implode(',', $imgAccept);
    $vidAcceptAttr = implode(',', $vidAccept);
@endphp

<div x-data="filesSelector({
    docsName: @js($docsName),
    imagesName: @js($imagesName),
    videosName: @js($videosName),

    maxDocs: @js((int) $maxDocs),
    maxImages: @js((int) $maxImages),
    maxVideos: @js((int) $maxVideos),

    maxDoc: @js((int) $maxDocMb) * 1024 * 1024,
    maxImg: @js((int) $maxImgMb) * 1024 * 1024,
    maxVid: @js((int) $maxVidMb) * 1024 * 1024,

    docAccept: @js($docAccept),
    imgAccept: @js($imgAccept),
    vidAccept: @js($vidAccept),
})" class="w-full">
    <h1 class="text-2xl mb-5">{{ $label }}</h1>

    {{-- верхние карточки-загрузчики --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Документы --}}
        <label
            class="cursor-pointer rounded-2xl bg-[#f6f8fe] p-6 border border-transparent hover:border-primary/30 block flex flex-col items-center justify-center"
            @dragover.prevent @drop.prevent="handleDrop($event, 'docs')">
            <div class="flex items-start justify-center gap-1">
                <x-lucide-file-text class="w-6 h-6 text-primary" />
                <div class="text-primary font-medium">Документ</div>
            </div>
            <div class="mt-2">
                <div class="text-sm text-gray-500">Допустимые форматы pdf</div>
                <div class="text-sm text-gray-500">Количество документов {{ $maxDocs }}</div>
            </div>
            <input type="file" class="hidden" multiple x-ref="docsInput" name="{{ $docsName }}[]"
                accept="{{ $docAcceptAttr }}" @change="handleSelect($event, 'docs')" />
        </label>

        {{-- Фотографии --}}
        <label
            class="cursor-pointer rounded-2xl bg-[#f6f8fe] p-6 border border-transparent hover:border-primary/30 block flex flex-col items-center justify-center"
            @dragover.prevent @drop.prevent="handleDrop($event, 'images')">
            <div class="flex items-start justify-center gap-1">
                <x-lucide-image class="w-6 h-6 text-primary" />
                <div class="text-primary font-medium">Фотография</div>
            </div>
            <div class="mt-2">
                <div class="text-sm text-gray-500">Допустимые форматы png, jpg</div>
                <div class="text-sm text-gray-500">Количество фотографий {{ $maxImages }}</div>
            </div>
            <input type="file" class="hidden" multiple x-ref="imagesInput" name="{{ $imagesName }}[]"
                accept="{{ $imgAcceptAttr }}" @change="handleSelect($event, 'images')" />
        </label>

        {{-- Видео --}}
        <label
            class="cursor-pointer rounded-2xl bg-[#f6f8fe] p-6 border border-transparent hover:border-primary/30 block flex flex-col items-center justify-center"
            @dragover.prevent @drop.prevent="handleDrop($event, 'videos')">
            <div class="flex items-start justify-center gap-1">
                <x-lucide-clapperboard class="w-6 h-6 text-primary" />
                <div class="text-primary font-medium">Видео</div>
            </div>
            <div class="mt-2">
                <div class="text-sm text-gray-500">Загрузка файла</div>
                <div class="text-sm text-gray-500">Количество видео {{ $maxVideos }}</div>
            </div>
            <input type="file" class="hidden" multiple x-ref="videosInput" name="{{ $videosName }}[]"
                accept="{{ $vidAcceptAttr }}" @change="handleSelect($event, 'videos')" />
        </label>
    </div>

    {{-- ошибки глобально --}}
    <p class="mt-3 text-sm text-red-600" x-text="error" x-show="error"></p>

    {{-- Списки и превью --}}
    <div class="mt-6 space-y-6">

        {{-- Документы --}}
        <template x-if="docs.length">
            <div>
                <div class="text-gray-700 font-medium mb-2">
                    Загружено документов <span x-text="docs.length"></span>/{{ $maxDocs }}
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <template x-for="(f, i) in docs" :key="f._id">
                        <div class="flex items-center justify-between rounded-xl bg-[#f6f8fe] px-4 py-3">
                            <div class="min-w-0">
                                <div class="truncate text-gray-700" x-text="f.file.name"></div>
                                <div class="text-xs text-gray-500"
                                    x-text="(f.file.size/1024/1024).toFixed(1) + ' МБ, ' + (f.file.type || 'PDF')">
                                </div>
                            </div>
                            <button type="button" class="p-2 hover:bg-gray-100 rounded-lg" @click="remove('docs', i)">
                                <x-lucide-x class="w-4 h-4 text-gray-500" />
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- Фотографии --}}
        <template x-if="images.length">
            <div>
                <div class="text-gray-700 font-medium mb-2">
                    Загружено фотографий <span x-text="images.length"></span>/{{ $maxImages }}
                </div>
                <div class="flex flex-wrap gap-3">
                    <template x-for="(f, i) in images" :key="f._id">
                        <div class="relative w-28 h-20 rounded-xl overflow-hidden bg-[#fff7ea]">
                            <img :src="f.url" alt="" class="w-full h-full object-cover">
                            <button type="button" class="absolute right-2 top-2 bg-white rounded-full shadow p-1"
                                @click="remove('images', i)">
                                <x-lucide-x class="w-4 h-4 text-gray-600" />
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- Видео (маленькие превью/плашки) --}}
        <template x-if="videos.length">
            <div>
                <div class="text-gray-700 font-medium mb-2">
                    Загружено видео <span x-text="videos.length"></span>/{{ $maxVideos }}
                </div>
                <div class="flex flex-wrap gap-3">
                    <template x-for="(f, i) in videos" :key="f._id">
                        <div
                            class="relative w-40 h-24 rounded-xl overflow-hidden bg-black/5 flex items-center justify-center">
                            <video :src="f.url" class="h-full" muted></video>
                            <button type="button" class="absolute right-2 top-2 bg-white rounded-full shadow p-1"
                                @click="remove('videos', i)">
                                <x-lucide-x class="w-4 h-4 text-gray-600" />
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>

@once
    @push('scripts')
        <script>
            function filesSelector(cfg) {
                const uid = () => Math.random().toString(36).slice(2, 9);

                return {
                    error: '',
                    // списки выбранных файлов (объекты: {_id, file, url?})
                    docs: [],
                    images: [],
                    videos: [],

                    // рефы на инпуты
                    getInputRef(type) {
                        return this.$refs[type + 'Input'];
                    },

                    // проверка лимитов и типов
                    validateFile(file, type) {
                        const accept = type === 'docs' ? cfg.docAccept : type === 'images' ? cfg.imgAccept : cfg.vidAccept;
                        const max = type === 'docs' ? cfg.maxDoc : type === 'images' ? cfg.maxImg : cfg.maxVid;

                        if (!accept.includes(file.type)) {
                            this.error = 'Недопустимый формат файла.';
                            return false;
                        }
                        if (file.size > max) {
                            this.error = 'Размер файла превышает допустимый лимит.';
                            return false;
                        }
                        return true;
                    },

                    // синхронизируем FileList в реальном input через DataTransfer
                    syncInput(type) {
                        const input = this.getInputRef(type);
                        const dt = new DataTransfer();
                        const list = this[type];

                        list.forEach(item => dt.items.add(item.file));
                        input.files = dt.files;
                    },

                    pushFiles(files, type) {
                        const maxCount = type === 'docs' ? cfg.maxDocs : type === 'images' ? cfg.maxImages : cfg.maxVideos;
                        const list = this[type];

                        for (const file of files) {
                            this.error = '';
                            if (list.length >= maxCount) {
                                this.error = 'Достигнут лимит количества.';
                                break;
                            }
                            if (!this.validateFile(file, type)) break;

                            const item = {
                                _id: uid(),
                                file
                            };
                            if (type !== 'docs') {
                                item.url = URL.createObjectURL(file);
                            }
                            list.push(item);
                        }
                        this.syncInput(type);
                    },

                    handleSelect(e, type) {
                        if (!e.target.files?.length) return;
                        this.pushFiles(e.target.files, type);
                        // очистим value, чтобы можно было выбрать те же файлы повторно
                        e.target.value = '';
                    },

                    handleDrop(e, type) {
                        const files = e.dataTransfer?.files;
                        if (!files || !files.length) return;
                        this.pushFiles(files, type);
                    },

                    remove(type, index) {
                        const item = this[type][index];
                        if (item?.url) URL.revokeObjectURL(item.url);
                        this[type].splice(index, 1);
                        this.syncInput(type);
                    },
                }
            }
        </script>
    @endpush
@endonce
