@props([
    // имя input'а (обязательно)
    'name',

    // подпись слева (необяз.)
    'label' => null,

    // уже сохранённая картинка (URL или /storage/...)
    'value' => null,

    // принимаемые типы
    'accept' => ['image/png','image/jpeg'],

    // лимит размера файла в МБ
    'maxMb' => 2,

    // подсказка под загрузчиком
    'help' => 'Допустимые форматы png, jpeg. Размер не должен превышать :maxMb Mb.',
])

@php
    $acceptAttr = implode(',', $accept);
@endphp

<div
    x-data="imageSelector({
        name: @js($name),
        initialUrl: @js($value),
        maxSize: @js((int)$maxMb) * 1024 * 1024,
        accept: @js($accept)
    })"
    class="w-full"
>
    @if($label)
        <label class="block text-2xl font-semibold text-gray-900 mb-5">{{ $label }}</label>
    @endif

    <div class="flex flex-col sm:flex-row items-start gap-2">
        {{-- drop-zone --}}
        <div
            x-on:dragover.prevent="drag=true"
            x-on:dragleave.prevent="drag=false"
            x-on:drop.prevent="handleDrop($event)"
            class="flex-1 rounded-2xl border border-dashed h-[150px]"
            :class="drag ? 'border-primary/60 bg-primary/5' : 'border-gray-300 bg-[#f7f9fd]'"
        >
            <label class="block cursor-pointer p-6 text-center h-full flex flex-col justify-center">
                <input type="file"
                       class="hidden"
                       :name="name"
                       accept="{{ $acceptAttr }}"
                       x-ref="file"
                       @change="handleFile($event)" />

                <div class="space-y-1">
                    <div class="flex items-center justify-center gap-1 text-primary">
                        <x-lucide-download class="w-5 h-5" />
                        <span class="font-medium">Добавьте файл</span>
                        <span class="text-gray-700 hidden sm:inline">или перетащите сюда</span>
                    </div>

                    <div class="text-gray-500 text-sm">
                        <div x-text="`Допустимые форматы ${accept.map(a=>a.split('/')[1]).join(', ')}.`"></div>
                        <div x-text="`Размер не должен превышать ${Math.round(maxSize/1024/1024)}Mb.`"></div>
                    </div>
                </div>
            </label>
        </div>

        {{-- preview card --}}
        <template x-if="previewUrl || initialUrl">
            <div class="relative w-full sm:w-4/12 h-[150px] mt-2 sm:mt-0">
                <div class="bg-[#fff3e0] rounded-xl overflow-hidden h-full">
                    <img :src="previewUrl || initialUrl"
                         alt="preview"
                         class="w-full h-full object-cover rounded-xl">
                </div>
                <button type="button"
                        @click="removeImage()"
                        class="absolute -right-2 -top-2 bg-white rounded-full shadow p-1 hover:bg-gray-50">
                    <x-lucide-x class="w-5 h-5 text-gray-600"/>
                </button>
            </div>
        </template>
    </div>

    {{-- ошибка валидации (клиент/сервер) --}}
    <p class="mt-3 text-sm text-red-600" x-text="error" x-show="error"></p>
    @error($name)
        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
    @enderror

    {{-- скрытое поле, если пользователь удалил уже сохранённую картинку --}}
    <input type="hidden" :name="`${name}_remove`" x-model="removeFlag">
</div>

@once
@push('scripts')
<script>
function imageSelector({ name, initialUrl = null, maxSize = 2*1024*1024, accept = ['image/png','image/jpeg'] }) {
    return {
        name,
        initialUrl,
        previewUrl: null,
        error: '',
        drag: false,
        maxSize,
        accept,
        removeFlag: 0,

        handleFile(e) {
            this.error = '';
            const file = e.target.files[0];
            if (!file) return;

            if (!this.accept.includes(file.type)) {
                this.error = 'Недопустимый формат файла.';
                e.target.value = '';
                return;
            }
            if (file.size > this.maxSize) {
                this.error = 'Размер файла превышает допустимый лимит.';
                e.target.value = '';
                return;
            }
            this.previewUrl = URL.createObjectURL(file);
            this.removeFlag = 0; // выбрали новый файл — значит не удаляем
        },

        handleDrop(event) {
            this.drag = false;
            const dt = event.dataTransfer;
            if (!dt || !dt.files || !dt.files.length) return;
            // положим file в input, чтобы ушёл обычной multipart‑формой
            this.$refs.file.files = dt.files;
            this.handleFile({ target: this.$refs.file });
        },

        removeImage() {
            // очищаем выбранный файл и превью; отмечаем удаление сохранённой картинки
            this.previewUrl = null;
            if (this.$refs.file) this.$refs.file.value = '';
            if (this.initialUrl) {
                this.removeFlag = 1; // серверу сказать "удалить текущее значение"
                this.initialUrl = null;
            }
        }
    }
}
</script>
@endpush
@endonce
